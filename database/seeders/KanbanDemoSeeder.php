<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KanbanDemoSeeder extends Seeder
{
    public function run()
    {
        // Criar usuário de teste
        $user = User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@kanban.com',
            'password' => Hash::make('password'),
        ]);

        // Criar quadros
        $board1 = Board::create([
            'name' => 'Projeto Website',
            'description' => 'Projeto Website',
            'user_id' => $user->id,
        ]);

        $board2 = Board::create([
            'name' => 'Tarefas Pessoais',
            'description' => 'Tarefas Pessoais',
            'user_id' => $user->id,
        ]);

        // Colunas padrão
        $columns = [
            ['title' => 'To Do', 'order' => 0],
            ['title' => 'Doing', 'order' => 1],
            ['title' => 'Done', 'order' => 2],
        ];

        // Adicionar colunas e tarefas para o primeiro quadro
        $this->createColumnsAndTasks($board1, $columns, [
            'To Do' => [
                ['title' => 'Criar layout inicial', 'description' => 'Design das páginas principais'],
                ['title' => 'Configurar banco de dados', 'description' => 'Criar migrações e modelos'],
            ],
            'Doing' => [
                ['title' => 'Desenvolver autenticação', 'description' => 'Sistema de login e registro'],
            ],
            'Done' => [
                ['title' => 'Requisitos do projeto', 'description' => 'Documentar necessidades do cliente'],
            ],
        ]);

        // Adicionar colunas e tarefas para o segundo quadro
        $this->createColumnsAndTasks($board2, $columns, [
            'To Do' => [
                ['title' => 'Compras do mês', 'description' => 'Fazer lista de supermercado'],
                ['title' => 'Marcar médico', 'description' => 'Check-up anual'],
            ],
            'Doing' => [
                ['title' => 'Ler livro novo', 'description' => '30 páginas por dia'],
            ],
            'Done' => [
                ['title' => 'Pagar contas', 'description' => 'Energia e internet'],
            ],
        ]);
    }

    private function createColumnsAndTasks(Board $board, array $columns, array $tasksByColumn)
    {
        foreach ($columns as $columnData) {
            $column = Column::create([
                'title' => $columnData['title'],
                'order' => $columnData['order'],
                'board_id' => $board->id,
            ]);

            if (isset($tasksByColumn[$columnData['title']])) {
                foreach ($tasksByColumn[$columnData['title']] as $index => $taskData) {
                    Task::create([
                        'title' => $taskData['title'],
                        'description' => $taskData['description'],
                        'order' => $index,
                        'column_id' => $column->id,
                    ]);
                }
            }
        }
    }
}
