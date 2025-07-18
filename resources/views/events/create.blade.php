@extends('layouts.main')

@section('title', 'Criar Evento')

@section('content')

<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Crie o seu evento</h1>
    <form action="/events" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Exibir todos os erros no topo --}}
            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <div class="form-group">
        <label for="image">Imagem do Evento:</label>
        <label class="custom-file-upload" for="image">
        <input type="file" id="image" name="image" class="form-control-file  @error('image') input-error @enderror">
            Escolher Arquivo
        </label>
        <span id="file-name">Nenhum arquivo selecionado</span>
        <script>
            document.getElementById('image').addEventListener('change', function(){
            const fileName = this.files.length ? this.files[0].name : 'Nenhum arquivo selecionado';
            document.getElementById('file-name').textContent = fileName;
            });
        </script>
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div><br>


        <div class="form-group">
            <label for="title">Evento:</label>
            <input type="text" class="form-control  @error('title') input-error @enderror" id="title" name="title" placeholder="Nome do evento">
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="date">Data do Evento:</label>
            <input type="date" class="form-control  @error('date') input-error @enderror" id="date" name="date">
            @error('date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">Cidade:</label>
            <input type="text" class="form-control  @error('city') input-error @enderror" id="city" name="city" placeholder="Local do evento">
            @error('city')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="title">O evento é privado?</label>
            <select name="private" id="private" class="form-control">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>
            
        </div>

        <div class="form-group">
            <label for="title">Descrição:</label>
            <textarea name="description" id="description" class="form-control  @error('description') input-error @enderror" placeholder="O que vai acontecer no seu evento?"></textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror

        </div>
        
        <div class="form-group">
            <label for="title">Adicione itens de infraestrutura:</label>
            <div class="form-group">
               <input type="checkbox" name="items[]" value="Cadeiras"> Cadeiras 
            </div>

            <div class="form-group">
               <input type="checkbox" name="items[]" value="Palco"> Palco
            </div>

            <div class="form-group">
               <input type="checkbox" name="items[]" value="Cerveja Grátis"> Cerveja Grátis
            </div>

            <div class="form-group">
               <input type="checkbox" name="items[]" value="Open Food"> Open Food 
            </div>

            <div class="form-group">
               <input type="checkbox" name="items[]" value="Brindes"> Brindes
            </div>

            <div class="form-group">
                <input type="checkbox" name="items[]" value="Nenhum Item"> Nenhum Item

            </div>

            @error('items')
                <div class="text-danger">{{ $message }}</div>
             @enderror
        </div>
        <input type="submit" class="btn btn-primary" value="Criar evento">
        
    </form>
</div>

@endsection
