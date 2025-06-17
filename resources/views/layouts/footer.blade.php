<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h3 class="footer-title">Sobre o Projeto</h3>
                <p>Este sistema Kanban foi desenvolvido com Laravel, Bootstrap e JavaScript para demonstrar habilidades
                    em desenvolvimento web fullstack.</p>
            </div>

            <div class="col-lg-4 mb-4 mb-lg-0">
                <h3 class="footer-title">Links Rápidos</h3>
                <div class="footer-links">
                    <a href="{{ route('home') }}"><i class="fas fa-home me-2"></i> Home</a>
                    <a href="{{ route('boards.create') }}"><i class="fas fa-plus-circle me-2"></i> Criar Quadro</a>
                </div>
            </div>

            <div class="col-lg-4">
                <h3 class="footer-title">Conecte-se</h3>
                <div class="social-links">
                    <a href="https://bruno-herculano.dev.br/" target="_blank" title="Portfólio">
                        <i class="fas fa-globe"></i>
                    </a>
                    <a href="https://github.com/bruno-herculano" target="_blank" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/bruno-alexandre-herculano/" target="_blank" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Bruno Alexandre Herculano. Todos os direitos reservados.</p>
            <p class="mb-0">Desenvolvido com <i class="fas fa-heart text-danger"></i> usando Laravel e Bootstrap</p>
        </div>
    </div>
</footer>
