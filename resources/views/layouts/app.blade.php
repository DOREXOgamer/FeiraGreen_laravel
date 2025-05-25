<!DOCTYPE html>
<html>
<head>
    <title>FeiraGreen</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<header>
    <div class="logo">
        <a href="{{ url('/') }}"><img src="{{ asset('imagens/logoverde.png') }}" alt="Logo" width="200"></a>
    </div>
<div id="area-menu">
    <a href="{{ route('categoria.exibir', 'frutas') }}">Frutas</a>
    <a href="{{ route('categoria.exibir', 'verduras') }}">Verduras</a>
    <a href="{{ route('categoria.exibir', 'hortalicas') }}">Hortaliças</a>
    <a href="{{ route('categoria.exibir', 'legumes') }}">Legumes</a>
    <a href="{{ route('categoria.exibir', 'outros') }}">Outros</a>

</div>

    <nav>
        <form method="GET" action="{{ url('buscar') }}" class="barra-pesquisa">
           <input type="text" name="termo" placeholder="Buscar produto..." required>
            <button type="submit" class="botao-pesquisa">
                <img src="{{ asset('imagens/pesquisa.png') }}" alt="Pesquisar" width="20">
            </button>
        </form>
        <a href="{{ url('perfil') }}"><img src="{{ asset('imagens/usuario.png') }}" alt="Usuário" width="20"></a>
        <a href="{{ url('carrinho')  }}"><img src="{{ asset('imagens/carrinho-carrinho.png') }}" alt="Carrinho" width="20"></a>
    </nav>
</header>

<div class="line"></div>

<main>
    @yield('content')
</main>

<div class="line"></div>

<footer>
    <div class="footer">
        <div class="footer-top">
            <div class="footer-top--left">
                <a href="#">Contato</a>
                <a href="#">Termos de Serviço</a>
                <a href="#">Política de Privacidade</a>
                <a href="#">Cancelamento, Troca e Reembolso</a>
            </div>
            <div class="footer-top--right">
                <span>Boletim de Notícias</span>
                <div class="footer-news-letter">
                    <input class="footer-input" type="email" placeholder="Digite o seu e-mail">
                    <button class="footer-button" type="button">Inscrever</button>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom--left">
                <a href="#"><img class="footer-image" src="{{ asset('imagens/instagram.png') }}" alt=""></a>
                <a href="#"><img class="footer-image" src="{{ asset('imagens/facebook.png') }}" alt=""></a>
            </div>
            <div>
                &copy; {{ date('Y') }} FeiraGreen. Todos os direitos reservados.
            </div>
            <div class="footer-bottom--right">
                <img class="footer-image" src="{{ asset('imagens/mastercard.png') }}" alt="">
                <img class="footer-image" src="{{ asset('imagens/paypal.png') }}" alt="">
                <img class="footer-image" src="{{ asset('imagens/visa.png') }}" alt="">
            </div>
        </div>
    </div>
</footer>
</body>
</html>
