@extends('back.layout.template')

@section('title', 'Detalhes do Artigo - Admin')

@section('content')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Detalhes do Artigo</h1>
        </div>

        <div class="mt-3">

            <table class="table table-striped table-bordered" id="dataTable">
                <tr>
                    <th width="250px">Título</th>
                    <td>: {{ $article->title }}</td>
                </tr>
                <tr>
                    <th>Categoria</th>
                    <td>: {{ $article->Category->name }}</td>
                </tr>
                <tr>
                    <th>Descrição</th>
                    <td>: {{ $article->desc }}</td>
                </tr>
                <tr>
                    <th>Imagem</th>
                    <td><img src="{{ asset('storage/back/' . $article->img) }}" alt="" width="200px"></td>
                </tr>
                <tr>
                    <th>Visualizações</th>
                    <td>: {{ $article->views }}x</td>
                </tr>
                <tr>
                    <th>Status</th>
                    @if ($article->status == 0)
                        <td><span class="badge bg-danger">Privado</span></td>
                    @else
                        <td><span class="badge bg-success">Publicado</span></td>
                    @endif
                </tr>
                <tr>
                    <th>Data da Publicação</th>
                    <td>: {{ \Carbon\Carbon::parse($article->publish_date)->format('d/m/Y') }}</td>
                </tr>
            </table>

            <div class="float-end mb-3">
                <a href="{{ url('articles') }}" class="btn btn-secondary">Voltar</a>
            </div>

        </div>

    </main>
@endsection
