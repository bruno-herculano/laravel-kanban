<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Editar Tarefa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="editTaskId">

                    <div class="mb-3">
                        <label for="editTaskTitle" class="form-label">Título</label>
                        <input type="text" class="form-control bg-secondary border-dark text-light" id="editTaskTitle"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Descrição</label>
                        <textarea class="form-control bg-secondary border-dark text-light" id="editTaskDescription"
                            rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editTaskPriority" class="form-label">Prioridade</label>
                        <select class="form-select bg-secondary border-dark text-light" id="editTaskPriority">
                            <option value="none">Sem prioridade</option>
                            <option value="low">Baixa</option>
                            <option value="medium">Média</option>
                            <option value="high">Alta</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editTaskColumn" class="form-label">Coluna</label>
                        <select class="form-select bg-secondary border-dark text-light" id="editTaskColumn">
                            @foreach($board->columns as $column)
                                <option value="{{ $column->id }}">{{ $column->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEditTaskBtn">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function () {
            // Quando clicar em uma tarefa para editar
            $(document).on('click', '.kanban-task', function () {
                const taskId = $(this).data('task-id');
                const taskTitle = $(this).find('.kanban-task-title').text();
                const taskDescription = $(this).find('.kanban-task-description').text().trim();
                const taskPriority = $(this).attr('class').match(/task-priority-(\w+)/)[1];
                const taskColumnId = $(this).closest('.kanban-column-body').data('column-id');

                $('#editTaskId').val(taskId);
                $('#editTaskTitle').val(taskTitle);
                $('#editTaskDescription').val(taskDescription);
                $('#editTaskPriority').val(taskPriority);
                $('#editTaskColumn').val(taskColumnId);

                $('#editTaskModal').modal('show');
            });

            // Salvar edição da tarefa
            $('#saveEditTaskBtn').click(function () {
                const taskId = $('#editTaskId').val();

                $.ajax({
                    url: `/tasks/${taskId}`,
                    method: 'PUT',
                    data: {
                        title: $('#editTaskTitle').val(),
                        description: $('#editTaskDescription').val(),
                        priority: $('#editTaskPriority').val(),
                        column_id: $('#editTaskColumn').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Atualiza a tarefa na interface
                        const taskElement = $(`.kanban-task[data-task-id="${taskId}"]`);
                        taskElement.find('.kanban-task-title').text(response.title);

                        if (response.description) {
                            taskElement.find('.kanban-task-description').text(response.description);
                        } else {
                            taskElement.find('.kanban-task-description').remove();
                        }

                        // Atualiza a classe de prioridade
                        taskElement.removeClass(function (index, className) {
                            return (className.match(/(^|\s)task-priority-\S+/g) || []).join(' ');
                        }).addClass(`task-priority-${response.priority || 'none'}`);

                        // Se mudou de coluna, move o elemento
                        if (response.column_id != taskElement.closest('.kanban-column-body').data('column-id')) {
                            $(`.kanban-column-body[data-column-id="${response.column_id}"]`)
                                .prepend(taskElement);
                        }

                        $('#editTaskModal').modal('hide');

                        Swal.fire(
                            'Sucesso!',
                            'Tarefa atualizada com sucesso.',
                            'success'
                        );
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Erro!',
                            'Não foi possível atualizar a tarefa.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endpush
