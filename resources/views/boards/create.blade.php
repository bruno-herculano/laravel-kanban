@extends('layouts.app')

@section('title', 'Criar Novo Quadro')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-light border-light">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Criar Novo Quadro
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('boards.store') }}" id="createBoardForm">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Nome do Quadro</label>
                            <input id="name" type="text"
                                   class="form-control bg-secondary border-dark text-light @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea id="description" rows="3"
                                      class="form-control bg-secondary border-dark text-light @error('description') is-invalid @enderror"
                                      name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Criar Quadro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    document.getElementById('createBoardForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Criando seu quadro...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                e.target.submit();
            }
        });
    });
</script>
@endpush
@endsection
