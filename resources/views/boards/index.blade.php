@extends('layouts.app')

@section('title', 'Meus Quadros')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-light"><i class="fas fa-chalkboard me-2"></i>Meus Quadros</h1>
            <a href="{{ route('boards.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Novo Quadro
            </a>
        </div>

        <div class="row g-4">
            @foreach($boards as $index => $board)

                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="board-card card h-100 bg-white">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold mb-0 text-dark">{{ $board->name }}</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm text-dark" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('boards.show', $board) }}">
                                                <i class="fas fa-eye me-2"></i>Abrir
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('boards.edit', $board) }}">
                                                <i class="fas fa-pencil me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('boards.destroy', $board) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger"
                                                    onclick="confirmDelete(event)">
                                                    <i class="fas fa-trash me-2"></i>Excluir
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="text-dark card-text flex-grow-1 ">
                                {{ Str::limit($board->description, 100) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center text-dark">
                                <small>
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $board->created_at->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('boards.show', $board) }}" class="btn btn-sm btn-primary">
                                    Abrir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('js')

    @if (Session::has('criado'))
        <script>
            Swal.fire(
                'Criado !',
                'O quadro foi criado',
                'success'
            );
        </script>
    @endif

    @if (Session::has('removido'))
        <script>
            Swal.fire(
                'Removido !',
                'O quadro foi removido',
                'success'
            );
        </script>
    @endif

@endpush
