<div class="modal fade" id="editColumnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Renomear Coluna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editColumnForm">
                    <input type="hidden" id="editColumnId">
                    <div class="mb-3">
                        <label for="editColumnTitle" class="form-label">Novo Nome</label>
                        <input type="text" class="form-control bg-secondary border-dark text-light" id="editColumnTitle"
                            required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEditColumnBtn">Salvar</button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function () {
            $('#saveEditColumnBtn').click(function () {
                const columnId = $('#editColumnId').val();
                const newTitle = $('#editColumnTitle').val();

                $.ajax({
                    url: `/columns/${columnId}`,
                    method: 'PUT',
                    data: {
                        title: newTitle,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        $(`.kanban-column[data-column-id="${columnId}"] .kanban-column-title`)
                            .text(newTitle);
                        $('#editColumnModal').modal('hide');

                        Swal.fire(
                            'Sucesso!',
                            'Coluna renomeada com sucesso.',
                            'success'
                        );
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Erro!',
                            'Não foi possível renomear a coluna.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endpush
