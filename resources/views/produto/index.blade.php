@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/produto-add.css') }}">
@endsection

@section('content')
<div class="produto-add-container">

  <h1>Meus Produtos</h1>

  {{-- Mensagem de sucesso --}}
  @if(session('success'))
    <div class="produto-add-alert produto-add-alert-success">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
  @endif

  {{-- === Seção de Adicionar Produto ==== --}}
  <div class="produto-add-section">
    <h2>Adicionar Produto</h2>
    <form action="{{ route('produto.add') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-6">
          {{-- Nome --}}
          <div class="produto-add-form-group">
            <label for="nome" class="produto-add-label">Nome do Produto:</label>
              <input type="text" id="nome" name="nome"
                value="{{ old('nome') }}" required maxlength="50"
                pattern="[A-Za-zÀ-ÿ\s]+" title="Apenas letras são permitidas"
                class="produto-add-control @error('nome') is-invalid @enderror">

            @error('nome')
              <div class="produto-add-invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Preço --}}
          <div class="produto-add-form-group">
            <label for="preco" class="produto-add-label">Preço:</label>
            <div class="input-group">
              <span class="input-group-text">R$</span>
              <input type="number" step="0.01" id="preco" name="preco"
                value="{{ old('preco') }}" required max="5000"
                oninput="if (this.value > 5000) this.value = 5000;"
                class="produto-add-control @error('preco') is-invalid @enderror">

              @error('preco')
                <div class="produto-add-invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="col-md-6">
          {{-- Categoria --}}
          <div class="produto-add-form-group">
            <label for="categoria" class="produto-add-label">Categoria:</label>
            <select id="categoria" name="categoria" required
                    class="produto-add-control @error('categoria') is-invalid @enderror">
              <option value="">Selecione uma categoria</option>
              @foreach(['Frutas','Verduras','Hortaliças','Legumes','Outros'] as $cat)
                <option value="{{ $cat }}" {{ old('categoria')==$cat?'selected':'' }}>
                  {{ $cat }}
                </option>
              @endforeach
            </select>
            @error('categoria')
              <div class="produto-add-invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Imagem --}}
          <div class="produto-add-form-group">
            <label for="imagem" class="produto-add-label">Imagem do Produto:</label>
            <input type="file" id="imagem" name="imagem"
                  accept="image/*"
                  class="produto-add-control produto-add-file-input @error('imagem') is-invalid @enderror">
            @error('imagem')
              <div class="produto-add-invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="produto-add-btn produto-add-btn-success">
          <i class="fas fa-plus me-2"></i>Adicionar Produto
        </button>
      </div>
    </form>
  </div>
  <hr class="produto-add-hr">
  {{-- === Fim seção de adicionar ==== --}}

  {{-- Lista de produtos --}}
  <h2>Produtos Cadastrados</h2>
  
  @if($produtos->isEmpty())
    <div class="produto-add-alert produto-add-alert-info">
      <i class="fas fa-info-circle me-2"></i>Você não possui produtos cadastrados.
    </div>
  @else
    <div class="produto-add-list-group">
      @foreach($produtos as $produto)
        <div class="produto-add-list-item">
          <div class="d-flex align-items-center">
            <div class="produto-thumb me-3">
              <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}" 
                   alt="{{ $produto->nome }}" 
                   onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';"
                   width="60" height="60" style="object-fit: cover; border-radius: 6px;">
            </div>
            <div>
              <h5>{{ $produto->nome }}</h5>
              <small>R$ {{ number_format($produto->preco,2,',','.') }} — {{ $produto->categoria }}</small>
            </div>
          </div>
          <div>
            <a href="{{ route('produto.edit', $produto->id) }}" class="produto-add-btn produto-add-btn-primary produto-add-btn-sm me-2">
              <i class="fas fa-edit me-1"></i>Editar
            </a>
            <form action="{{ route('produto.delete', $produto->id) }}" method="POST" style="display:inline-block;"
                  onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
              @csrf @method('DELETE')
              <button class="produto-add-btn produto-add-btn-danger produto-add-btn-sm">
                <i class="fas fa-trash-alt me-1"></i>Deletar
              </button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
    
    {{-- Paginação (se existir) --}}
    @if(method_exists($produtos, 'links'))
      <div class="mt-4">
        {{ $produtos->links() }}
      </div>
    @endif
  @endif

</div>
@endsection