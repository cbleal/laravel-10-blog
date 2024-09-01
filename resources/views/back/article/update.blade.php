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
                    <input type="file" name="img" id="img" class="form-control"
                        onchange="previewImage(event)" />
                    <div class="mt-1">
                        <small>Imagem</small><br>
                        <img src="{{ asset('storage/back/' . $article->img) }}" class="img-thumbnail img-preview"
                            width="100px">
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
@endpush
