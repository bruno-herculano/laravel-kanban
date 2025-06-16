@extends('layouts.app')

@section('title', $board->name)

@push('css')

@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-light mb-0">
                <i class="fas fa-chalkboard me-2"></i>{{ $board->name }}
            </h1>
            @if($board->description)
                <p class="text-light mb-0">{{ $board->description }}</p>
            @endif
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary" id="addColumnBtn">
                <i class="fas fa-plus me-1"></i> Adicionar Coluna
            </button>
            <button class="btn btn-success" id="addTaskBtn">
                <i class="fas fa-tasks me-1"></i> Adicionar Tarefa
            </button>
        </div>
    </div>

    <div class="kanban-container mx-auto" id="kanbanBoard">
        @foreach($board->columns as $column)
        <div class="kanban-column column-color-{{ $column->color_index ?? (($loop->index % 4) + 1) }}"
             data-column-id="{{ $column->id }}">
            <div class="kanban-column-header" data-column-id="{{ $column->id }}">
                <h3 class="kanban-column-title">{{ $column->title }}</h3>
                <div class="dropdown">
                    <button class="kanban-task-action"
                            type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li>
                            <button class="dropdown-item rename-column"
                                    data-column-id="{{ $column->id }}">
                                <i class="fas fa-pencil-alt me-2"></i>Renomear
                            </button>
                        </li>
                        <li>
                            <form class="delete-column-form"
                                  action="{{ route('columns.destroy', $column) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="dropdown-item text-danger delete-column">
                                    <i class="fas fa-trash me-2"></i>Excluir
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="kanban-column-body" data-column-id="{{ $column->id }}">
                @foreach($column->tasks as $task)
                <div class="kanban-task task-priority-{{ $task->priority ?? 'none' }}"
                     data-task-id="{{ $task->id }}" draggable="true">
                    <div class="kanban-task-header">
                        <h4 class="kanban-task-title">{{ $task->title }}</h4>
                        <div class="kanban-task-actions">
                            <button class="kanban-task-action edit-task"
                                    data-task-id="{{ $task->id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="kanban-task-action delete-task"
                                    data-task-id="{{ $task->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    @if($task->description)
                    <div class="kanban-task-description">
                        {{ Str::limit($task->description, 100) }}
                    </div>
                    @endif

                    <div class="kanban-task-footer">
                        <span>
                            <i class="far fa-calendar me-1"></i>
                            {{ $task->created_at->format('d/m/Y') }}
                        </span>
                        <span class="badge bg-dark">
                           <i class="fa-solid fa-star me-1"></i>
                            {{ $task->priority ?? 'Sem prioridade' }}
                        </span>
                    </div>
                </div>
                @endforeach

                <div class="add-task-btn" data-column-id="{{ $column->id }}">
                    <i class="fas fa-plus me-1"></i> Adicionar Tarefa
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@include('modals.add-column')
@include('modals.add-task')
@include('modals.edit-column')
@include('modals.edit-task')
@endsection

@push('js')

<script>
$(document).ready(function() {
    // Configuração do drag-and-drop para colunas (somente pelo cabeçalho)
    const kanbanBoard = document.getElementById('kanbanBoard');
    new Sortable(kanbanBoard, {
        animation: 200,
        handle: '.kanban-column-header',
        ghostClass: 'kanban-column-ghost',
        chosenClass: 'kanban-column-chosen',
        dragClass: 'kanban-column-drag',
        onStart: function(evt) {
            evt.item.classList.add('dragging');
        },
        onEnd: function(evt) {
            evt.item.classList.remove('dragging');

            // Obtém a nova ordem das colunas
            const columnIds = [];
            document.querySelectorAll('.kanban-column').forEach(col => {
                columnIds.push(col.dataset.columnId);
            });

            // Atualiza a ordem no servidor
            $.ajax({
                url: '/columns/reorder',
                method: 'POST',
                data: {
                    order: columnIds,
                    board_id: "{{ $board->id }}",
                    _token: '{{ csrf_token() }}'
                },
                error: function(xhr) {
                    console.error('Erro ao reordenar colunas:', xhr.responseText);
                    window.location.reload();
                }
            });
        }
    });

    // Configuração do drag-and-drop para tarefas
    document.querySelectorAll('.kanban-column-body').forEach(columnBody => {
        new Sortable(columnBody, {
            group: 'tasks',
            animation: 150,
            handle: '.kanban-task',
            ghostClass: 'kanban-task-ghost',
            chosenClass: 'kanban-task-chosen',
            dragClass: 'kanban-task-drag',
            onEnd: function(evt) {
                const taskId = evt.item.dataset.taskId;
                const newColumnId = evt.to.closest('.kanban-column').dataset.columnId;
                const newPosition = evt.newIndex;

                $.ajax({
                    url: `/tasks/${taskId}/move`,
                    method: 'POST',
                    data: {
                        column_id: newColumnId,
                        position: newPosition,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        // Sucesso silencioso
                    },
                    error: function(xhr) {
                        console.error('Erro ao mover tarefa:', xhr.responseText);
                        window.location.reload();
                    }
                });
            }
        });
    });

    // Adicionar coluna
    $('#addColumnBtn').click(function() {
        $('#addColumnModal').modal('show');
    });

    // Adicionar tarefa
    $('#addTaskBtn, .add-task-btn').click(function() {
        const columnId = $(this).data('column-id');
        $('#taskColumn').val(columnId || '');
        $('#addTaskModal').modal('show');
    });

    // Renomear coluna
    $('.rename-column').click(function() {
        const columnId = $(this).data('column-id');
        const columnTitle = $(this).closest('.kanban-column').find('.kanban-column-title').text();

        $('#editColumnId').val(columnId);
        $('#editColumnTitle').val(columnTitle);
        $('#editColumnModal').modal('show');
    });

    // Deletar coluna
    $('.delete-column').click(function() {
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Excluir coluna?',
            text: 'Todas as tarefas nesta coluna serão excluídas permanentemente!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Editar tarefa
    $('.edit-task').click(function(e) {
        e.stopPropagation();
        const taskId = $(this).data('task-id');
        const taskElement = $(this).closest('.kanban-task');

        const taskData = {
            id: taskId,
            title: taskElement.find('.kanban-task-title').text(),
            description: taskElement.find('.kanban-task-description').text().trim() || '',
            priority: taskElement.attr('class').match(/task-priority-(\w+)/)[1],
            column_id: taskElement.closest('.kanban-column').data('column-id')
        };

        $('#editTaskId').val(taskData.id);
        $('#editTaskTitle').val(taskData.title);
        $('#editTaskDescription').val(taskData.description);
        $('#editTaskPriority').val(taskData.priority);
        $('#editTaskColumn').val(taskData.column_id);

        $('#editTaskModal').modal('show');
    });

    // Deletar tarefa
    $('.delete-task').click(function(e) {
        e.stopPropagation();
        const taskId = $(this).data('task-id');

        Swal.fire({
            title: 'Excluir tarefa?',
            text: 'Esta ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/tasks/${taskId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $(`[data-task-id="${taskId}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                        Swal.fire(
                            'Excluído!',
                            'A tarefa foi removida.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Erro!',
                            'Não foi possível excluir a tarefa.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush
