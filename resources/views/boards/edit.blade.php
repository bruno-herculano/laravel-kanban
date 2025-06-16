@extends('layouts.app')

@section('title', 'Editar Quadro: ' . $board->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-light border-light">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Quadro: {{ $board->name }}
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('boards.update', $board) }}" id="editBoardForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="boardName" class="form-label">Nome do Quadro</label>
                            <input type="text" class="form-control bg-secondary border-dark text-light @error('name') is-invalid @enderror"
                                   id="boardName" name="name" value="{{ old('name', $board->name) }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="boardDescription" class="form-label">Descrição</label>
                            <textarea class="form-control bg-secondary border-dark text-light @error('description') is-invalid @enderror"
                                      id="boardDescription" name="description" rows="3">{{ old('description', $board->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('boards.show', $board) }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .color-option {
        position: relative;
    }

    .color-label {
        display: block;
        width: 40px;
        height: 40px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 0.25rem;
    }

    .color-label:hover {
        transform: scale(1.1);
    }

    input[type="radio"]:checked + .color-label {
        border-color: #fff;
        box-shadow: 0 0 0 2px #000;
    }
</style>
@endpush

@push('js')
<script>
    document.getElementById('editBoardForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Mostrar o spinner no botão
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Salvando...';
        submitButton.disabled = true;

        this.submit();
    });
</script>
@endpush
@endsection
