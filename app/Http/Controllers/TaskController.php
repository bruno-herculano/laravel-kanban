<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:none,low,medium,high',
            'column_id' => 'required|exists:columns,id'
        ]);

        $column = Column::find($request->column_id);
        $this->authorize('update', $column->board);

        $task = $column->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority ?? 'none',
            'order' => $column->tasks()->count(),
            'user_id' => auth()->id()
        ]);

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task->column->board);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:none,low,medium,high',
            'column_id' => 'required|exists:columns,id'
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('update', $task->column->board);

        $task->delete();
        return response()->json(['success' => true]);
    }

    public function move(Request $request, Task $task)
    {
        $this->authorize('update', $task->column->board);

        $request->validate([
            'column_id' => 'required|exists:columns,id',
            'position' => 'required|integer'
        ]);

        // Atualiza a coluna se necessário
        if ($task->column_id != $request->column_id) {
            $task->column_id = $request->column_id;
        }

        // Atualiza a ordem
        $task->order = $request->position;
        $task->save();

        // Reordena as tarefas na coluna de origem (se mudou de coluna)
        if ($task->wasChanged('column_id')) {
            Task::where('column_id', $task->getOriginal('column_id'))
                ->where('order', '>', $task->getOriginal('order'))
                ->decrement('order');
        }

        // Reordena as tarefas na coluna de destino
        Task::where('column_id', $task->column_id)
            ->where('id', '!=', $task->id)
            ->where('order', '>=', $task->order)
            ->increment('order');

        return response()->json(['success' => true]);
    }
}
