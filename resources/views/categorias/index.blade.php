@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/categoria.css') }}">
@endsection

@section('content')
<div class="categoria-container">
    <!-- Breadcrumb -->
    <nav class="categoria-breadcrumb">
        <a href="{{ route('home') }}">Início</a>
        <span>/</span>
        <span>{{ ucfirst($categoria) }}</span>
    </nav>

    <h1>{{ ucfirst($categoria) }}</h1>

    @if($produtos->isEmpty())
        <div class="categoria-vazia">
            <div class="categoria-vazia-icon">
                <i class="fas fa-search"></i>
            </div>
            <h2 class="categoria-vazia-titulo">Nenhum produto encontrado</h2>
            <p class="categoria-vazia-texto">
                Não encontramos produtos na categoria "{{ ucfirst($categoria) }}" no momento.
            </p>
            <a href="{{ route('home') }}" class="categoria-vazia-btn">
                <i class="fas fa-arrow-left"></i>
                Voltar para Início
            </a>
        </div>
    @else
        <!-- Apenas informações da categoria -->
        <div class="categoria-filtros">
            <div class="categoria-info">
                <i class="fas fa-box"></i>
                {{ $produtos->count() }} {{ $produtos->count() == 1 ? 'produto encontrado' : 'produtos encontrados' }}
            </div>
        </div>

        <div class="produtos-grid">
            @foreach($produtos as $produto)
                <div class="produto-container-principal {{ $produto->organico ? 'produto-organico' : '' }}">
                    @if($produto->organico)
                        <div class="produto-badge">
                            <i class="fas fa-leaf"></i>
                            Orgânico
                        </div>
                    @endif
                    
                    <a href="{{ route('produto.show', $produto->id) }}" aria-label="Ver detalhes do produto {{ $produto->nome }}">
                        <img src="{{ $produto->imagem ? asset('imagens/product_images/' . $produto->imagem) : asset('imagens/product_images/default-product.jpg') }}" 
                            alt="{{ $produto->nome }}" 
                            class="card-img-top" 
                            onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';" />
                    </a>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $produto->nome }}</h5>
                        <p class="card-text">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        <p class="card-text text-muted">{{ $produto->categoria }}</p>
                        
                        
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação (se necessário) -->
        @if(method_exists($produtos, 'links'))
            <div class="categoria-paginacao">
                {{ $produtos->links() }}
            </div>
        @endif
    @endif
</div>

<script>
// Animação de entrada dos produtos
document.addEventListener('DOMContentLoaded', function() {
    const produtos = document.querySelectorAll('.produto-container-principal');
    produtos.forEach((produto, index) => {
        produto.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection