<div class="modal fade" id="addColumnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Adicionar Coluna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="newColumnTitle" class="form-label">Título da Coluna</label>
                    <input type="text" class="form-control bg-secondary border-dark text-light" id="newColumnTitle"
                        required>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveColumnBtn">Salvar</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function () {
            $('#saveColumnBtn').click(function () {
                const title = $('#newColumnTitle').val().trim();
                const boardId = "{{ $board->id }}";

                if (!title) {
                    Swal.fire('Erro', 'Por favor, insira um título para a coluna.', 'error');
                    return;
                }

                $.ajax({
                    url: "{{ route('columns.store') }}",
                    method: 'POST',
                    data: {
                        title: title,
                        board_id: boardId,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#saveColumnBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Salvando...');
                    },
                    success: function (response) {
                        // Fecha o modal
                        $('#addColumnModal').modal('hide');
                        // Limpa o input
                        $('#newColumnTitle').val('');

                        // Adiciona a nova coluna no kanban
                        const newColumnHtml = `
                        <div class="kanban-column column-color-${Math.floor(Math.random() * 4) + 1}"
                             data-column-id="${response.id}">
                            <div class="kanban-column-header" data-column-id="${response.id}">
                                <h3 class="kanban-column-title">${response.title}</h3>
                                <div class="dropdown">
                                    <button class="kanban-task-action"
                                            type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li>
                                            <button class="dropdown-item rename-column"
                                                    data-column-id="${response.id}">
                                                <i class="fas fa-pencil-alt me-2"></i>Renomear
                                            </button>
                                        </li>
                                        <li>
                                            <form class="delete-column-form"
                                                  action="/columns/${response.id}"
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
                            <div class="kanban-column-body" data-column-id="${response.id}">
                                <div class="add-task-btn" data-column-id="${response.id}">
                                    <i class="fas fa-plus me-1"></i> Adicionar Tarefa
                                </div>
                            </div>
                        </div>
                    `;

                        $('#kanbanBoard').append(newColumnHtml);

                        // Re-inicializa o Sortable para as colunas
                        initColumnSortable();

                        Swal.fire('Sucesso!', 'Coluna adicionada com sucesso.', 'success');
                    },
                    error: function (xhr) {
                        Swal.fire('Erro!', 'Não foi possível adicionar a coluna.', 'error');
                    },
                    complete: function () {
                        $('#saveColumnBtn').prop('disabled', false).html('Salvar');
                    }
                });
            });
        });

        function initColumnSortable() {
            // Re-inicializa o Sortable para colunas
            new Sortable(document.getElementById('kanbanBoard'), {
                animation: 200,
                handle: '.kanban-column-header',
                ghostClass: 'kanban-column-ghost',
                chosenClass: 'kanban-column-chosen',
                dragClass: 'kanban-column-drag',
                onStart: function (evt) {
                    evt.item.classList.add('dragging');
                },
                onEnd: function (evt) {
                    evt.item.classList.remove('dragging');

                    const columnIds = [];
                    document.querySelectorAll('.kanban-column').forEach(col => {
                        columnIds.push(col.dataset.columnId);
                    });

                    $.ajax({
                        url: '/columns/reorder',
                        method: 'POST',
                        data: {
                            order: columnIds,
                            board_id: "{{ $board->id }}",
                            _token: '{{ csrf_token() }}'
                        },
                        error: function (xhr) {
                            console.error('Erro ao reordenar colunas:', xhr.responseText);
                            window.location.reload();
                        }
                    });
                }
            });
        }
    </script>
@endpush
