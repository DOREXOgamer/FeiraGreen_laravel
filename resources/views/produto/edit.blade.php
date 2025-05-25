@extends('layouts.main')

@section('content')
<div class="container produto-edit-container">
    <h1>Editar Produto</h1>

    <form action="{{ route('produto.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nome" class="form-label">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required class="form-control">
            @error('nome')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="preco" class="form-label">Preço:</label>
            <input type="number" step="0.01" id="preco" name="preco" value="{{ old('preco', $produto->preco) }}" required class="form-control">
            @error('preco')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="categoria" class="form-label">Categoria:</label>
            <select id="categoria" name="categoria" required class="form-control">
                <option value="">Selecione uma categoria</option>
                <option value="Frutas" {{ old('categoria', $produto->categoria) == 'Frutas' ? 'selected' : '' }}>Frutas</option>
                <option value="Verduras" {{ old('categoria', $produto->categoria) == 'Verduras' ? 'selected' : '' }}>Verduras</option>
                <option value="Hortaliças" {{ old('categoria', $produto->categoria) == 'Hortaliças' ? 'selected' : '' }}>Hortaliças</option>
                <option value="Legumes" {{ old('categoria', $produto->categoria) == 'Legumes' ? 'selected' : '' }}>Legumes</option>
                <option value="Outros" {{ old('categoria', $produto->categoria) == 'Outros' ? 'selected' : '' }}>Outros</option>
            </select>
            @error('categoria')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="imagem" class="form-label">Imagem do Produto:</label><br>
            @if($produto->imagem)
                <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}" alt="Imagem do produto" class="produto-imagem-preview mb-3" style="max-width: 200px;">
            @endif
            <input type="file" id="imagem" name="imagem" accept="image/*" class="form-control">
            @error('imagem')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
    </form>

    <form action="{{ route('produto.delete', $produto->id) }}" method="POST" class="mt-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Deletar Produto</button>
    </form>

    <a href="{{ route('perfil') }}" class="btn btn-secondary mt-3">Voltar ao Perfil</a>
</div>
@endsection
