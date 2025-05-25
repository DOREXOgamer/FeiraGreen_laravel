@if (is_array($imagens) && count($imagens) > 0)
    <div class="carousel">
        @php $first = true; @endphp
        @foreach ($imagens as $key => $imagem)
            <div class="banner-image" style="display: {{ $first ? 'block' : 'none' }}">
                <img src="{{ asset($imagem['imagem_path']) }}" alt="{{ $imagem['alt_text'] }}">
                @if(isset($imagem['link']))
                    <a href="{{ $imagem['link'] }}" class="banner-cta">Saiba mais</a>
                @endif
            </div>
            @php $first = false; @endphp
        @endforeach
        
        <div class="carousel-controls">
            @for ($i = 0; $i < count($imagens); $i++)
                <div class="carousel-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></div>
            @endfor
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentIndex = 0;
            const images = document.querySelectorAll('.banner-image');
            const dots = document.querySelectorAll('.carousel-dot');
            
            // Função para mostrar uma imagem específica
            function showImage(index) {
                if (images.length === 0) return;
                
                // Esconde a imagem atual
                images[currentIndex].style.display = 'none';
                dots[currentIndex].classList.remove('active');
                
                // Atualiza o índice
                currentIndex = index;
                
                // Mostra a nova imagem
                images[currentIndex].style.display = 'block';
                dots[currentIndex].classList.add('active');
            }
            
            // Função para mostrar a próxima imagem
            function showNextImage() {
                const nextIndex = (currentIndex + 1) % images.length;
                showImage(nextIndex);
            }
            
            // Configura o intervalo para trocar as imagens
            const interval = setInterval(showNextImage, 5000);
            
            // Adiciona eventos de clique aos pontos de navegação
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    clearInterval(interval); // Pausa o intervalo automático
                    showImage(index);
                    // Reinicia o intervalo
                    setTimeout(() => {
                        setInterval(showNextImage, 5000);
                    }, 100);
                });
            });
        });
    </script>
@else
    <div class="alert alert-warning">Carrossel indisponível.</div>
@endif