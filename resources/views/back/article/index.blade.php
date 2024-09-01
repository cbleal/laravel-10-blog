@extends('back.layout.template')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
@endpush

@section('title', 'Lista de Artigos - Admin')

@section('content')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Artigos</h1>
        </div>

        <div class="mt-3">
            <a href="{{ url('articles/create') }}" class="btn btn-success btn-sm mb-2">
                Criar
            </a>

            {{-- Msg Erros --}}
            <div class="my-3">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Msg Sucesso --}}
            {{-- <div class="my-3">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div> --}}

            {{-- alert-success --}}
            <div class="swal" data-swal="{{ session('success') }}"></div>

            <table class="table table-striped table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Visualizações</th>
                        <th>Status</th>
                        <th>Criado Em</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- @forelse ($articles as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->Category->name }}</td>
                            <td>0x</td>
                            @if ($item->status == 0)
                                <td>
                                    <span class="badge bg-danger">Privado</span>
                                </td>
                            @else
                                <td>
                                    <span class="badge bg-success">Publicado</span>
                                </td>
                            @endif
                            <td>{{ $item->publish_date }}</td>
                            <td>
                                <div class="text-center">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalUpdate{{ $item->id }}">
                                        Detalhes
                                    </button>

                                    <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalUpdate{{ $item->id }}">
                                        Editar
                                    </button>

                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalDelete{{ $item->id }}">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <p>Não existem registros...</p>
                    @endforelse --}}
                </tbody>
            </table>
        </div>

        <!-- Modal Create -->
        {{-- @include('back.category.create-modal') --}}
        <!-- Modal Create -->
        {{-- @include('back.category.update-modal') --}}
        <!-- Modal Delete -->
        {{-- @include('back.category.delete-modal') --}}

    </main>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const swal = document.querySelector('.swal');
        const msg = swal.getAttribute('data-swal');

        if (msg) {
            Swal.fire({
                icon: "success",
                text: msg,
                title: "Sucesso",
                showConfirmButton: false,
                timer: 2000
            });
        }

        function deleteArticle(el) {
            const id = el.getAttribute('data-id');
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Esta ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se confirmado, enviar uma requisição DELETE para a rota de remoção
                    fetch(`/articles/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deletado!',
                                    data.success,
                                    'success'
                                ).then(() => {
                                    // Recarrega a página ou remove o item da lista
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Erro!',
                                    data.error,
                                    'error'
                                );
                            }
                        })
                        .catch((error) => {
                            Swal.fire(
                                'Erro!',
                                'Ocorreu um erro ao tentar deletar o registro.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>

    <script>
        new DataTable('#dataTable', {
            processing: true,
            serverside: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'title',
                    name: 'title',
                },
                {
                    data: 'category_id',
                    name: 'category_id',
                },
                {
                    data: 'views',
                    name: 'views',
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'publish_date',
                    name: 'publish_date',
                },
                {
                    data: 'button',
                    name: 'button',
                },
            ]
        });
    </script>
@endpush
