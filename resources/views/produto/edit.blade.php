@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/produto-edit.css') }}">
@endsection

@section('content')
<div class="container produto-edit-container">
    <h1>Editar Produto</h1>

    {{-- Formulário principal de edição --}}
    <form action="{{ route('produto.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" id="nome" name="nome" 
                   value="{{ old('nome', $produto->nome) }}" required 
                   maxlength="50"
                   pattern="[A-Za-zÀ-ÿ\s]+"
                   title="Apenas letras são permitidas"
                   class="form-control @error('nome') is-invalid @enderror">
            @error('nome')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" id="preco" name="preco" 
                   value="{{ old('preco', $produto->preco) }}" required 
                   class="form-control @error('preco') is-invalid @enderror">
            @error('preco')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="categoria" class="form-label">Categoria</label>
            <select id="categoria" name="categoria" required 
                    class="form-control @error('categoria') is-invalid @enderror">
                <option value="">Selecione uma categoria</option>
                @foreach(['Frutas','Verduras','Hortaliças','Legumes','Outros'] as $cat)
                    <option value="{{ $cat }}" {{ old('categoria', $produto->categoria) == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
            @error('categoria')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Imagem do Produto</label>
            <div class="imagem-upload-section {{ $produto->imagem ? 'has-image' : '' }}">
                @if($produto->imagem)
                    <div class="imagem-preview-container">
                        <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}" 
                             alt="Imagem atual do produto" 
                             class="produto-imagem-preview"
                             onerror="this.style.display='none'">
                        <div class="upload-info-current">
                            <i class="fas fa-check-circle"></i> Imagem atual do produto
                        </div>
                    </div>
                @endif
                
                <div class="upload-area">
                    <label for="imagem" class="upload-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        {{ $produto->imagem ? 'Alterar Imagem' : 'Escolher Imagem' }}
                    </label>
                    <input type="file" id="imagem" name="imagem" accept="image/*" 
                           class="upload-input @error('imagem') is-invalid @enderror">
                    
                    <div class="upload-info">
                        @if($produto->imagem)
                            Selecione uma nova imagem para substituir a atual
                        @else
                            Formatos aceitos: JPG, PNG, GIF (máx. 2MB)
                        @endif
                    </div>
                </div>
                
                @error('imagem')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="btn-container-primary">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar Produto
            </button>
        </div>
    </form>
</div>

{{-- Script opcional para impedir números dinamicamente (extra) --}}
<script>
    document.getElementById('nome').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');
    });
</script>
@endsection
