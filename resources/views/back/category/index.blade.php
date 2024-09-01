@extends('back.layout.template')

@section('title', 'Lista de Categorias - Admin')

@section('content')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Categorias</h1>
      </div>

      <div class="mt-3">
        <button class="btn btn-success btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalCreate">
            Criar
        </button>

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
        <div class="my-3">
            @if (session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @endif
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Criado Em</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $item)                    
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->slug}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <div class="text-center">
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdate{{$item->id}}">
                                    Editar
                                </button>
                                <button class="btn btn-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#modalDelete{{$item->id}}">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p>Não existem registros...</p>
                @endforelse
            </tbody>
        </table>
      </div>

      <!-- Modal Create -->
      @include('back.category.create-modal')
      <!-- Modal Create -->
      @include('back.category.update-modal')
      <!-- Modal Delete -->
      @include('back.category.delete-modal')
      
    </main>
@endsection  
