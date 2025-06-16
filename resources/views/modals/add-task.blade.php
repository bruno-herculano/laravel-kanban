<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Adicionar Tarefa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="taskTitle" class="form-label">Título</label>
                    <input type="text" class="form-control bg-secondary border-dark text-light" id="taskTitle" required>
                </div>

                <div class="mb-3">
                    <label for="taskDescription" class="form-label">Descrição</label>
                    <textarea class="form-control bg-secondary border-dark text-light" id="taskDescription"
                        rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="taskPriority" class="form-label">Prioridade</label>
                    <select class="form-select bg-secondary border-dark text-light" id="taskPriority">
                        <option value="none">Sem prioridade</option>
                        <option value="low">Baixa</option>
                        <option value="medium">Média</option>
                        <option value="high">Alta</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="taskColumn" class="form-label">Coluna</label>
                    <select class="form-select bg-secondary border-dark text-light" id="taskColumn">
                        @foreach($board->columns as $column)
                            <option value="{{ $column->id }}">{{ $column->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn">Salvar</button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function () {
            $('#saveTaskBtn').click(function () {
                const title = $('#taskTitle').val().trim();
                const description = $('#taskDescription').val().trim();
                const priority = $('#taskPriority').val();
                const columnId = $('#taskColumn').val();

                if (!title) {
                    Swal.fire('Erro', 'Por favor, insira um título para a tarefa.', 'error');
                    return;
                }

                $.ajax({
                    url: "{{ route('tasks.store') }}",
                    method: 'POST',
                    data: {
                        title: title,
                        description: description,
                        priority: priority,
                        column_id: columnId,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#saveTaskBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Salvando...');
                    },
                    success: function (response) {
                        // Fecha o modal
                        $('#addTaskModal').modal('hide');
                        // Limpa os campos
                        $('#taskTitle').val('');
                        $('#taskDescription').val('');
                        $('#taskPriority').val('none');

                        // Adiciona a nova tarefa na coluna correspondente
                        const taskHtml = `
                        <div class="kanban-task task-priority-${response.priority || 'none'}"
                             data-task-id="${response.id}">
                            <div class="kanban-task-header">
                                <h4 class="kanban-task-title">${response.title}</h4>
                                <div class="kanban-task-actions">
                                    <button class="kanban-task-action edit-task"
                                            data-task-id="${response.id}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="kanban-task-action delete-task"
                                            data-task-id="${response.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            ${response.description ? `
                            <div class="kanban-task-description">
                                ${response.description}
                            </div>
                            ` : ''}
                            <div class="kanban-task-footer">
                                <span>
                                    <i class="far fa-calendar me-1"></i>
                                    ${new Date().toLocaleDateString('pt-BR')}
                                </span>
                                <span class="badge bg-dark">
                                    <i class="fas fa-user me-1"></i>
                                    {{ Auth::user()->name }}
                                </span>
                            </div>
                        </div>
                    `;

                        $(`.kanban-column-body[data-column-id="${columnId}"] .add-task-btn`)
                            .before(taskHtml);

                        Swal.fire('Sucesso!', 'Tarefa adicionada com sucesso.', 'success');
                    },
                    error: function (xhr) {
                        Swal.fire('Erro!', 'Não foi possível adicionar a tarefa.', 'error');
                    },
                    complete: function () {
                        $('#saveTaskBtn').prop('disabled', false).html('Salvar');
                    }
                });
            });
        });
    </script>
@endpush
