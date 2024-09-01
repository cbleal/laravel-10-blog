# laravel-blog-10

## Criar models Category e Article e suas migrations

```
php artisan make:model Category -m
php artisan make:model Article -m
```

## Rodar migrate para criação das tabelas no banco laravel-blog

```
php artisan migrate
```

## Inserir registro na tabela category

## Criar um controller com todos os recursos

```
php artisan make:controller Back/CategoryController -r
```

## Criar as rotas no arquivo web.php

```
Route::resource('/categories', CategoryController::class)
```

## Visualizar as rotas criadas

```
php artisan route:list
```

## Em CategoryController no método index(), chamar a view index de category enviando as catergories recuperadas da tabela

```
public function index()
    {
        return view('back.category.index', [
            'categories' => Category::get()
        ]);
    }
```

## Criar a view index de category

```
php artisan make:view back/category/index
```

## Inserir link para categories em layout/sidebar

```
<li class="nav-item">
    <a class="nav-link" href="{{route('categories.index')}}">
    <span data-feather="shopping-cart" class="align-text-bottom"></span>
    Categorias
    </a>
</li>
```

## Criar tabela para mostrar as categories no arquivo html: back/category/index

```
<div class="mt-3">
    <button class="btn btn-success btn-sm mb-2">Criar</button>
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
                                <button class="btn btn-secondary btn-sm">Editar</button>
                                <button class="btn btn-danger btn-sm">Excluir</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p>Não existem registros...</p>
                @endforelse
            </tbody>
        </table>
</div>
```

## Criar a view create-modal em back/category

```
php artisan make:view back/category/create-modal
```

## Inserir o código na create-modal

```
<!-- Modal -->
<div class="modal fade" id="modalCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Nova Categoria</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{url('categories')}}" method="post">
            @csrf

            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control"  value="{{old('name')}}" />

            <div class="my-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
```

## Alterar o arquivo de rotas web.php apenas com as rotas necessárias

```
Route::resource('/categories', CategoryController::class)->only([
    'index', 'store', 'update', 'destroy'
]);
```

## Inserir o código bootstrap de validação no campo input de back/category/index

```
<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" />

    @error('name')
        <div class="invalid-feedback">
            {{$message}}
        </div>
    @enderror
```

## Definir os campo mass assignment na model Category

```
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];
}
```

## Implementa o método store() de CategoryController

```
public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:3'
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return back()->with('success', 'Categoria criada com sucesso...');
    }
```

## Inserir as visualizações de erro e sucesso em back/category/index

```
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
```

## Implementa o método update() de CategoryController

```
public function update(Request $request, string $id)
{
    # valida os dados
    $data = $request->validate([
        'name' => 'required|min:3'
    ]);

    # cria o slug
    $data['slug'] = Str::slug($data['name']);

    # atualiza a categoria
    Category::find($id)->update($data);

    # retorna com mensagem de sucesso
    return back()->with('success', 'Categoria atualizada com sucesso...');
}
```

### Cria a modal back/category/update-modal.blade.php

```
@foreach ($categories as $item)

  <div class="modal fade" id="modalUpdate{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Categoria</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{url('categories/'.$item->id)}}" method="post">
              @csrf
              @method('PUT')

              <label for="name">Nome</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name', $item->name)}}" />

              @error('name')
                  <div class="invalid-feedback">
                      {{$message}}
                  </div>
              @enderror

              <div class="my-2">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  @endforeach
```

### insere a chamada da modalUpdate no botão Edita em back/category/index.blade.php

```
<button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdate{{$item->id}}">Editar</button>
```

### insere a chamada da update-modal em back/category/index.blade.php

```
<!-- Modal Create -->
@include('back.category.update-modal')
```

## Implementa o método destroy() de CategoryController

```
public function destroy(string $id)
{
    Category::find($id)->delete();

    return back()->with('success', 'Categoria removida com sucesso...');
}
```

### Cria a modal back/category/delete-modal.blade.php

```
@foreach ($categories as $item)

  <div class="modal fade" id="modalDelete{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Excluir Categoria</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{url('categories/'.$item->id)}}" method="post">
              @csrf
              @method('DELETE')

              <div class="mb-3">
                <p>Tem certeza que deseja apagar a categoria {{$item->name}} ?</p>
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-danger">Excluir</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  @endforeach
```

### insere a chamada da modalUpdate no botão Excluir em back/category/index.blade.php

```
<button class="btn btn-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#modalDelete{{$item->id}}">Excluir</button>
```

### insere a chamada da delete-modal em back/category/index.blade.php

```
<!-- Modal Delete -->
@include('back.category.delete-modal')
```

## Implementa o método destroy() de CategoryController

```
public function destroy(string $id)
{
    Category::find($id)->delete();

    return back()->with('success', 'Categoria removida com sucesso...');
}
```

## Criar o controller ArticleController com todos os recursos

```
php artisan make:controller ArticleController -r
```

## Implementar o metodo index do ArticleController

```
public function index()
    {
        # mandar para view
        return view('back.article.index', [
            'articles' => Article::get(),
        ]);
    }
```

## Criar a view index em views/back/article/index.blade.php

```
???
```

## Criar a rota para articles em web.php

```
# Artigos
Route::resource('/articles', ArticleController::class);
```

## Fazer a chamada de articles no link da página views/layout/sidebar.blade.php

```
<li class="nav-item">
  <a class="nav-link" href="{{url('articles')}}">
    <span data-feather="file" class="align-text-bottom"></span>
    Artigos
  </a>
</li>
```

## Implementar a classe app/Models/Article

```
class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'image', 'desc', 'views', 'status', 'publish_date', 'category_id'];

    public function Category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

## Fazer a chamada do nome da categoria na view index em views/back/article/index.blade.php

```
<td>{{$item->Category->name}}</td>
```

## Fazer a chamada da página 'articles' em views/back/layout/sidebar.blade.php

```
<li class="nav-item">
  <a class="nav-link" href="{{url('articles')}}">
    <span data-feather="file" class="align-text-bottom"></span>
    Artigos
  </a>
</li>
```

## Adicionar o componente DataTable na página articles

```
1. no arquivo views/back/layout/template.blade.php adiconar as diretivas:


{{-- add css --}}
@stack('js')

{{-- add js --}}
@stack('js')

2. no arquivo views/back/article/index.blade.php adicionar as diretivas:

<!-- CSS -->
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
@endpush

<!-- JS -->
@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTable');
    </script>
@endpush
```

## Adicionar os titulos das páginas:

```
1.  em views/back/layout/template.blade.php adicionar:
<title>@yield('title')</title>

2.  em views/back/article/index.blade.php adicionar:
@section('title', 'Lista de Artigos - Admin')

3.  em views/back/dashboard/index.blade.php adicionar:
@section('title', 'Dashboard - Admin')

```

## Instalar biblioteca yajra-datatables
```
composer require yajra/laravel-datatables:^10.0"
```
## Registrar yajra-datatables provider e facades em: app/config.app
```
'providers' => [
    ...,
    Yajra\DataTables\DataTablesServiceProvider::class,
]

'aliases' => [
    ...,
    'DataTables' => Yajra\DataTables\Facades\DataTables::class,
]
```

## Redefine o método index() de ArticleController
```
public function index()
    {
        if (request()->ajax()) {
            $article = Article::with('Category')->latest()->get();

            return DataTables::of($article)
                ->addColumn('category_id', function ($article) {
                    return $article->Category->name;
                })
                ->addColumn('status', function ($article) {
                    if ($article->status == 0) {
                        return '<span class="badge bg-danger">Privado</span>';
                    } else {
                        return '<span class="badge bg-success">Publicado</span>';
                    }
                })
                ->addColumn('button', function ($article) {
                    return '<div class="text-center">
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
                                </div>';
                })
                ->rawColumns(['category_id', 'status', 'button'])
                ->make();
        }

        # mandar para view
        return view('back.article.index');
    }
```

## Reescreve a view index de views/back/article/index.blade.php
```
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
                        {{ session('success') }}
                    </div>
                @endif
            </div>

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
                
                </tbody>
            </table>
        </div>       

    </main>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTable', {
            processing: true,
            serverside: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    data: 'id',
                    name: 'id',
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

```

### Redefinição do link para criar novo article em back/article/index.blade.php
```
<a href="{{ url('articles/create') }}" class="btn btn-success btn-sm mb-2">
    Criar
</a>
```

### Criação da view back/article/create.blade.php
```
@extends('back.layout.template')

@section('title', 'Lista de Artigos - Admin')

@section('content')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Novo Artigo</h1>
        </div>

        <div class="mt-3">

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

            <form action="{{ url('articles') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title"
                                value="{{ old('title') }}" />
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" name="category_id" id="category_id"
                                value="{{ old('category_id') }}">
                                <option value="" hidden>-- Selecione--</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="desc">Descrição</label>
                    <textarea class="form-control" name="desc" id="desc" cols="30" rows="10">{{ old('desc') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="img">Imagem - max: 2024MB</label>
                    <input type="file" name="img" id="img" class="form-control" />
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" value="{{ old('status') }}">
                                <option value="" hidden>-- Selecione--</option>
                                <option value="1">Publicado</option>
                                <option value="0">Privado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="publish_date">Data Publicação</label>
                            <input type="date" name="publish_date" id="publish_date" class="form-control"
                                value="{{ old('publish_date') }}" />
                        </div>
                    </div>
                </div>

                <div class="float-end mb-3">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>

            </form>

        </div>

    </main>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
@endpush

```

### Codificação do método store em ArticleController
```
public function store(ArticleRequest $request)
  {
      // dd($request);

      # valida os dados do request e atribui a um array
      $data = $request->validated();
      # recupera o file do request
      $file = $request->file('img');
      // dd($file);
      # renomeia o arquivo com sua extensão original
      $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
      // dd($fileName);
      # armazena o arquivo
      $file->storeAs('public/back', $fileName);
      # atribui o nome do arquivo ao array de dados com a chave 'img'
      $data['img'] = $fileName;
      # cria o slug
      $data['slug'] = Str::slug($data['title']);
      // dd($data);
      # salva os dados
      Article::create($data);
      # redireciona para a página index de articles
      return redirect(url('articles'))->with('success', 'Artigo criado com sucesso...');
  }
 ```

## Ação para o botão Detalhes do Artigo: implementar em back/article/index.blade.php
```
<a href="articles/' . $article->id . '" class="btn btn-primary btn-sm">
    Detalhes
</a>
```
## Criar a view em back/article/show.blade.php
```
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
                    <td><img src="{{ asset('storage/back/' . $article->img) }}" alt="" width="50%"></td>
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
```
## Executar o comando para sincronizar a pasta storage para public
```
php artisan storage:link
```
## Edição do Artigo: direcionar o link do botão Editar em ArticleController
```
php artisan storage:link
```
## Implementar o metodo edit no ArticleController
```
public function edit(string $id): View
{
    return view('back.article.update', [
        'article' => Article::find($id),
        'categories' => Category::get()
    ]);
}
```

## Implementar a view back/article/update.blade.php
```
@extends('back.layout.template')

@section('title', 'Editar Artigo - Admin')

@section('content')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Editar Artigo</h1>
        </div>

        <div class="mt-3">

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

            <form action="{{ url('articles/' . $article->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="oldImg" value="{{ $article->img }}">

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title"
                                value="{{ old('title', $article->title) }}" />
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" name="category_id" id="category_id">
                                @foreach ($categories as $item)
                                    @if ($item->id == $article->id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="desc">Descrição</label>
                    <textarea class="form-control" name="desc" id="desc" cols="30" rows="10">{{ old('desc', $article->desc) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="img">Max: 2GB</label>
                    <input type="file" name="img" id="img" class="form-control" />
                    <small>Imagem</small>
                    <div class="mt-1">
                        <img src="{{ asset('storage/back/' . $article->img) }}" width="80px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="0" {{ $article->status == 0 ? 'selected' : null }}>Privado</option>
                                <option value="1" {{ $article->status == 1 ? 'selected' : null }}>Publicado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="publish_date">Data Publicação</label>
                            <input type="date" name="publish_date" id="publish_date" class="form-control"
                                value="{{ old('publish_date', $article->publish_date) }}" />
                        </div>
                    </div>
                </div>

                <div class="float-end mb-3">
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>

            </form>

        </div>

    </main>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
@endpush

```
## Criar o request validador do metodo update() de ArticleController
```
php artisan make:request UpdateArticleRequest
```
## Implementar o validador UpdateArticleRequest
```
public function authorize(): bool
{
    return true;
}

public function rules(): array
{
    return [
        'category_id' => 'required',
        'title' => 'required',
        'desc' => 'required',
        'img' => 'nullable|image|file|mimes:png,jpg,jpeg,webp|max:2024',
        'status' => 'required',
        'publish_date' => 'required',
    ];
}
```
## Implementar o metodo update do controller ArticleController
```
public function update(UpdateArticleRequest $request, string $id)
{
    $data = $request->validated();

    if ($request->hasFile('img')) {
        # recupera o file do request
        $file = $request->file('img');
        # renomeia o arquivo com sua extensão original
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        # armazena o arquivo
        $file->storeAs('public/back', $fileName);
        # apaga o arquivo img antigo
        Storage::delete('public/back' . $request->oldImg);
        # atribui o nome do arquivo ao array de dados com a chave 'img'
        $data['img'] = $fileName;
    } else {
        $data['img'] = $request->oldImg;
    }

    # cria o slug
    $data['slug'] = Str::slug($data['title']);
    # atualiza os dados
    Article::find($id)->update($data);
    # redireciona para a página index de articles
    return redirect(url('articles'))->with('success', 'Artigo editado com sucesso...');
}
```
### Instalar sweetalert2
```
no site: sweetalert2.github.io copiar o cdn:
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### Na view back/article/index.blade.php colar o cdn do sweetalert2
```
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### Na view back/article/index.blade.php substituir o bloco de mensagem de sucesso por:
```
{{-- alert-success --}}
<div class="swal" data-swal="{{ session('success') }}"></div>
```

### Implementar metodo destroy() do controller ArticleController
```
```

### Modifica o link do botão Excluir no controller ArticleController:
```
<a href="#" onclick="deleteArticle(this)" data-id="'.$article->id.'" class="btn btn-danger btn-sm">
    Excluir
</a>
```

### Implementar funcao deleteArticle() na view back/article/index.blade.php
```
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
```

### Alterar a view back/article/create.blade.php para visualização da imagem selecionada pra inserção:
```
1 - alteramos no formulário o campo da imagem:
<div class="mb-3">
    <label for="img">Imagem - max: 2024MB</label>
    <input type="file" name="img" id="img" class="form-control"
        onchange="previewImage(event)" />

    <div>
        <img src="#" class="img-thumbnail img-preview"
            style="display: none; max-width: 300px; margin-top: 10px;" />
    </div>
</div>

2 - criamos o script com a função previewImage(event):
<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.querySelector('.img-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
```
### Alterar a view back/article/update.blade.php para visualização da imagem selecionada pra edição:
```
1 - alteramos no formulário o campo da imagem:
<div class="mb-3">
    <label for="img">Max: 2GB</label>
    <input type="file" name="img" id="img" class="form-control"
        onchange="previewImage(event)" />
    <div class="mt-1">
        <small>Imagem</small><br>
        <img src="{{ asset('storage/back/' . $article->img) }}" class="img-thumbnail img-preview"
            width="100px">
    </div>
</div>

2 - criamos o script com a função previewImage(event):
<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.querySelector('.img-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
```
