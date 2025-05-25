@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/produto_show.css') }}">
@endsection

@section('content')
<div class="produto-detalhes-container">
    <!-- Breadcrumb -->
    <nav class="produto-breadcrumb">
        <a href="{{ route('home') }}">Início</a>
        <span>/</span>
        <a href="{{ route('home') }}">{{ $produto->categoria }}</a>
        <span>/</span>
        <span>{{ $produto->nome }}</span>
    </nav>

    <!-- Layout principal -->
    <div class="produto-detalhes-layout">
        <!-- Seção da imagem -->
        <div class="produto-imagem-section">
            @if($produto->imagem)
                <img src="{{ asset('imagens/product_images/' . $produto->imagem) }}" 
                     alt="Imagem do produto {{ $produto->nome }}" 
                     class="produto-imagem"
                     onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';">
            @else
                <img src="{{ asset('imagens/product_images/default-product.jpg') }}" 
                     alt="Imagem padrão" 
                     class="produto-imagem">
            @endif
            
            @if($produto->organico)
                <div class="produto-badge-organico">
                    <i class="fas fa-leaf me-1"></i>Orgânico
                </div>
            @endif
        </div>

        <!-- Seção de informações -->
        <div class="produto-info-section">
            <h1 class="produto-titulo">{{ $produto->nome }}</h1>
            
            <div class="produto-categoria">
                <i class="fas fa-tag me-1"></i>{{ $produto->categoria }}
            </div>
            
            <div class="produto-preco">
                <span class="produto-preco-label">Preço:</span>
                R$ {{ number_format($produto->preco, 2, ',', '.') }}
            </div>

            <!-- Ações -->
            <div class="produto-acoes">
                <form action="{{ route('cart.add', $produto->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn-produto btn-produto-primary">
                        <i class="fas fa-shopping-cart me-1"></i>
                        Adicionar ao Carrinho
                    </button>
                </form>
                
                <a href="{{ route('home') }}" class="btn-produto btn-produto-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Informações adicionais -->
    <div class="produto-info-adicional">
        <h3>Informações do Produto</h3>
        
        <div class="produto-caracteristicas">
            <div class="produto-caracteristica">
                <div class="produto-caracteristica-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="produto-caracteristica-texto">
                    <div class="produto-caracteristica-titulo">Categoria</div>
                    <div class="produto-caracteristica-valor">{{ $produto->categoria }}</div>
                </div>
            </div>
            
            <div class="produto-caracteristica">
                <div class="produto-caracteristica-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="produto-caracteristica-texto">
                    <div class="produto-caracteristica-titulo">Preço</div>
                    <div class="produto-caracteristica-valor">R$ {{ number_format($produto->preco, 2, ',', '.') }}</div>
                </div>
            </div>
            
            @if($produto->organico)
            <div class="produto-caracteristica">
                <div class="produto-caracteristica-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="produto-caracteristica-texto">
                    <div class="produto-caracteristica-titulo">Tipo</div>
                    <div class="produto-caracteristica-valor">Produto Orgânico</div>
                </div>
            </div>
            @endif
            
            <div class="produto-caracteristica">
                <div class="produto-caracteristica-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="produto-caracteristica-texto">
                    <div class="produto-caracteristica-titulo">Entrega</div>
                    <div class="produto-caracteristica-valor">Disponível na região</div>
                </div>
            </div>
            
            <div class="produto-caracteristica">
                <div class="produto-caracteristica-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="produto-caracteristica-texto">
                    <div class="produto-caracteristica-titulo">Origem</div>
                    <div class="produto-caracteristica-valor">Agricultura Familiar</div>
                </div>
            </div>
            
            <div class="produto-caracteristica">
                <div class="produto-caracteristica-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="produto-caracteristica-texto">
                    <div class="produto-caracteristica-titulo">Qualidade</div>
                    <div class="produto-caracteristica-valor">Produto Fresco</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection