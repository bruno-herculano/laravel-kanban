<?php
namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id'
        ]);

        $board = Board::find($request->board_id);
        $this->authorize('update', $board);

        $column = $board->columns()->create([
            'title' => $request->title,
            'order' => $board->columns()->count(),
            'color_index' => rand(1, 4) // Adiciona cor aleatória
        ]);

        return response()->json($column);
    }

    public function update(Request $request, Column $column)
    {
        $this->authorize('update', $column->board);

        $request->validate(['title' => 'required|string|max:255']);
        $column->update($request->only('title'));

        return response()->json($column);
    }

    public function destroy(Column $column)
    {
        $this->authorize('update', $column->board);

        $column->delete();

        return redirect()->route('boards.show', $column->board)
            ->with('success', 'Coluna excluida com sucesso!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'board_id' => 'required|exists:boards,id'
        ]);

        // Verifica se o usuário tem acesso ao board
        $board = Board::find($request->board_id);
        $this->authorize('update', $board);

        // Atualiza a ordem das colunas
        foreach ($request->order as $index => $columnId) {
            Column::where('id', $columnId)
                ->where('board_id', $board->id)
                ->update(['order' => $index]);
        }


        return response()->json(['success' => true]);
    }
}
