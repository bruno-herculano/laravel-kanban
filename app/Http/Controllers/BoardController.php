<?php
namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->boards;
        // dd(Board::get());
        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        return view('boards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'sometimes|integer|between:1,4'
        ]);

        $board = auth()->user()->boards()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'color_index' => $validated['color'] ?? 1
        ]);

        return redirect()->route('boards.index')->with('criado', 'Quadro criado com sucesso!');
    }

    public function show(Board $board)
    {
        $this->authorize('view', $board);

        // Carrega as colunas e tarefas
        $board->load([
            'columns' => function ($query) {
                $query->orderBy('order')->with([
                    'tasks' => function ($query) {
                        $query->orderBy('order');
                    }
                ]);
            }
        ]);

        return view('boards.show', compact('board'));
    }

    public function edit(Board $board)
    {
        $this->authorize('update', $board);

        return view('boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $board->update($validated);

        return redirect()->route('boards.show', $board)
            ->with('success', 'Quadro atualizado com sucesso!');
    }

    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);

        $board->delete();

        return redirect()->route('boards.index')->with('removido', 'Quadro excluido com sucesso!');
    }
}
