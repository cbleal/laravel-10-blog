@foreach ($categories as $item)

  <div class="modal fade" id="modalUpdate{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Atualizar Categoria</h1>
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

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  @endforeach