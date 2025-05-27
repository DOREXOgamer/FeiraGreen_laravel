@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/carrinho.css') }}">
@endsection

@section('content')
<div class="carrinho-container">
    <h1 class="carrinho-titulo">Seu Carrinho</h1>

    @if(session('success'))
        <div class="carrinho-alert carrinho-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="carrinho-alert carrinho-alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <div class="carrinho-layout">
            <div class="carrinho-produtos">
                <div class="carrinho-produtos-header">
                    <span class="carrinho-produtos-titulo">Produto</span>
                    <span class="carrinho-produtos-total">Total</span>
                </div>
                
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $itemTotal = $item['preco'] * $item['quantity']; $total += $itemTotal; @endphp
                    <div class="carrinho-item">
                        <img src="{{ $item['imagem'] ? asset('imagens/product_images/' . $item['imagem']) : asset('imagens/product_images/default-product.jpg') }}" 
                             alt="{{ $item['nome'] }}" 
                             class="carrinho-item-imagem"
                             onerror="this.onerror=null; this.src='{{ asset('imagens/product_images/default-product.jpg') }}';">
                        
                        <div class="carrinho-item-info">
                            <h3 class="carrinho-item-nome">{{ $item['nome'] }}</h3>
                            <p class="carrinho-item-origem">{{ $item['categoria'] ?? 'Agricultura Familiar' }}</p>
                            
                            <div class="carrinho-item-controles">
                                <div class="carrinho-quantidade-container">
                                    <form action="{{ route('cart.update', $id) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                        @csrf
                                        <button type="button" class="carrinho-quantidade-btn" onclick="decrementQuantity(this, {{ $id }})">
                                            <img src="{{ asset('imagens/menos.png') }}" alt="Diminuir" width="16" height="16">
                                        </button>

                                        <span class="carrinho-quantidade-display" id="qty-{{ $id }}">{{ $item['quantity'] }}</span>
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" class="quantity-input" id="input-{{ $id }}">
                                        <button type="button" class="carrinho-quantidade-btn" onclick="incrementQuantity(this, {{ $id }}, '{{ $item['nome'] }}')">
                                            <img src="{{ asset('imagens/mais.png') }}" alt="Aumentar" width="16" height="16">
                                        </button>
                                        <button type="submit" style="display: none;" class="update-btn"></button>
                                    </form>
                                </div>
                                
                                <form action="{{ route('cart.remove', $id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="carrinho-item-remover" onclick="return confirm('Tem certeza que deseja remover este item?')">
                                        <img src="{{ asset('imagens/lixo.png') }}" alt="Remover" width="16" height="16">
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="carrinho-item-preco" id="price-{{ $id }}">
                            R$ {{ number_format($itemTotal, 2, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="carrinho-resumo">
                <h3 class="carrinho-resumo-titulo">Resumo do Pedido</h3>
                
                <div class="carrinho-resumo-linha">
                    <span class="carrinho-resumo-label">Subtotal</span>
                    <span class="carrinho-resumo-valor" id="subtotal">R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
                
                <div class="carrinho-resumo-linha">
                    <span class="carrinho-resumo-label">Frete</span>
                    <span class="carrinho-resumo-valor">Grátis</span>
                </div>
                
                <div class="carrinho-resumo-linha carrinho-resumo-total">
                    <span class="carrinho-resumo-label">Total</span>
                    <span class="carrinho-resumo-valor" id="total-final">R$ {{ number_format($total + 15, 2, ',', '.') }}</span>
                </div>
                
                <button class="carrinho-btn-finalizar">
                    Finalizar Compra
                </button>
            </div>
        </div>
        
        <div class="carrinho-navegacao">
            <a href="{{ route('home') }}" class="carrinho-btn-continuar">
                <i class="fas fa-arrow-left"></i>
                Continuar Comprando
            </a>
            <button class="carrinho-btn-atualizar" onclick="updateAllQuantities()">
                Atualizar Carrinho
            </button>
        </div>
    @else
        <div class="carrinho-vazio">
            <div class="carrinho-vazio-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2 class="carrinho-vazio-titulo">Seu carrinho está vazio</h2>
            <p class="carrinho-vazio-texto">
                Que tal adicionar alguns produtos frescos da nossa feira?
            </p>
            <a href="{{ route('home') }}" class="carrinho-vazio-btn">
                <i class="fas fa-leaf"></i>
                Ver Produtos
            </a>
        </div>
    @endif
</div>

<!-- Modal de Limite -->
<div class="modal-overlay" id="limitModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Limite Atingido!</h3>
        <p class="modal-message" id="modalMessage">
            Você atingiu o limite máximo de unidades para este produto.
        </p>
        <button class="modal-btn" onclick="closeModal()">
            Entendi
        </button>
    </div>
</div>

<script>
// Dados dos produtos para cálculos
const productPrices = {
    @foreach($cart as $id => $item)
        {{ $id }}: {{ $item['preco'] }},
    @endforeach
};

const MAX_QUANTITY = 100; // Definindo o limite máximo

function showLimitModal(productName, limit) {
    const modal = document.getElementById('limitModal');
    const message = document.getElementById('modalMessage');
    
    message.innerHTML = `
        <strong>${productName}</strong><br>
        Você atingiu o limite máximo de <strong>${limit} unidades</strong> para este produto.<br>
        <small style="color: #999; margin-top: 0.5rem; display: block;">
            Para quantidades maiores, entre em contato conosco.
        </small>
    `;
    
    modal.classList.add('show');
    
    // Adicionar efeito de shake no botão
    const quantityContainer = event.target.closest('.carrinho-quantidade-container');
    quantityContainer.classList.add('shake');
    setTimeout(() => {
        quantityContainer.classList.remove('shake');
    }, 500);
}

function closeModal() {
    const modal = document.getElementById('limitModal');
    modal.classList.remove('show');
}

// Fechar modal ao clicar fora dele
document.getElementById('limitModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

function incrementQuantity(button, productId, productName) {
    const display = document.getElementById(`qty-${productId}`);
    const input = document.getElementById(`input-${productId}`);
    let quantity = parseInt(display.textContent);

    if (quantity < MAX_QUANTITY) {
        quantity++;
        display.textContent = quantity;
        input.value = quantity;
        updateItemPrice(productId, quantity);
        updateTotals();
    } else {
        showLimitModal(productName, MAX_QUANTITY);
    }
}

function decrementQuantity(button, productId) {
    const display = document.getElementById(`qty-${productId}`);
    const input = document.getElementById(`input-${productId}`);
    let quantity = parseInt(display.textContent);
    if (quantity > 1) {
        quantity--;
        display.textContent = quantity;
        input.value = quantity;
        updateItemPrice(productId, quantity);
        updateTotals();
    }
}

function updateItemPrice(productId, quantity) {
    const priceElement = document.getElementById(`price-${productId}`);
    const price = productPrices[productId];
    const total = price * quantity;
    priceElement.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

function updateTotals() {
    let subtotal = 0;
    Object.keys(productPrices).forEach(productId => {
        const quantity = parseInt(document.getElementById(`qty-${productId}`).textContent);
        const price = productPrices[productId];
        subtotal += price * quantity;
    });
    
    const frete = 0;
    const total = subtotal + frete;
    
    document.getElementById('subtotal').textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
    document.getElementById('total-final').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

function updateAllQuantities() {
    document.querySelectorAll('.update-btn').forEach(btn => {
        btn.click();
    });
}
</script>


@endsection