<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quartzo Azul</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/fontes.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/eventos.css">
  <link rel="stylesheet" href="css/footer.css">
  <script src="https://kit.fontawesome.com/94e8bd423f.js" crossorigin="anonymous"></script>
  <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
</head>
<body >
  <header class="header poly-regular">
    <img 
      src="img/logoHeader.png"
      sizes="(max-width: 768px) 100vw, 1024px"
      alt="Logo da empresa Quartzo Azul"
      class="header__logo"
    />
    
    <div class="header__content">
      <nav class="header__nav" aria-label="Menu principal">
        <ul class="header__nav-list">
          <li class="header__nav-item">
            <a href="#main__about" class="header__nav-link">Quem somos</a>
          </li>
          <li class="header__nav-item">
            <a href="#" class="header__nav-link">Eventos</a>
          </li>
        </ul>
      </nav>
      
      <div class="header__buttons">
        <button class="header__cta-button" aria-label="Comece agora">
          <a href="Cadastro.php" class="header__sign-up-link">Comece agora</a>
        </button>
        <span class="header__sign-in">
          <a href="Login.php" class="header__sign-in-link" aria-label="Sign in">Sign in</a>
        </span>
      </div>
    </div>
  </header>
  <main class="main">
 
    <div class="main__carousel">
      <img src="img/carrossel.png" alt="Slide 1" class="main__carousel-image" id="carousel-image">
    </div>

    <div class="main__about poly-regular" id="main__about">
      O gerenciador de eventos Quartzo Azul permite a criação, edição e organização de um evento. Com ferramentas como agendas, listas de convidados (onde você controla quem pode ou não participar do seu evento), organizador de tarefas e orçamentos, você consegue inovar e organizar seu próximo evento de maneira muito mais eficiente.
    </div>

    <section class="main__events kaisei-harunoumi-regular" id="main_events">
      <h1 class="main__events-title">Eventos</h1>
      <p class="main__events-text">Maximize o impacto dos seus eventos com nossas soluções completas em consultoria, gestão e logística.</p>
      <div class="main__events-cards"></div>
    </section>

    </main>

    <footer class="footer">
      <div class="footer__content">
      
          <div class="footer__sections">
              <div class="footer__column footer__column--logo">
                  <img src="img/logoFooter.png" alt="Logo Quartzo Azul">
              </div>
              <div class="footer__column">
                  <a class="footer__link"  href="main_about">Quem somos</a>
              </div>
              <div class="footer__column">
                  <a class="footer__link" href="main_events">Eventos</a>
              </div>
              <div class="footer__social">
                  <h3 class="footer__title">NOSSAS REDES SOCIAIS</h3>
                  <div class="footer__social-icons">
                      <a href="#" class="footer__social-link"><i class="fab fa-facebook-f"></i></a>
                      <a href="#" class="footer__social-link"><i class="fab fa-twitter"></i></a>
                      <a href="#" class="footer__social-link"><i class="fab fa-instagram"></i></a>
                      <a href="#" class="footer__social-link"><i class="fab fa-linkedin-in"></i></a>
                      <a href="#" class="footer__social-link"><i class="fab fa-youtube"></i></a>
                  </div>
              </div>
              <a href="#" class="footer__back">Voltar para o topo <i class="fa-solid fa-arrow-up"></i></a>
          </div>
        
      </div>
      <div class="footer__bottom">
          <div class="footer__bottom-group">
              <p class="footer__copyright">Grupo Quartzo Azul Copyright (c) 2024. Todos os direitos Reservados.</p>
              <p class="footer__address">Fatec Carapicuiba</p>
          </div>
          <p class="footer__credits">Desenvolvido pela BLAWD: Technology Agency.</p>
      </div>
  </footer>
  

    <script src="carrossel.js" defer type="module"></script>
    <script src="tiposDeEventos.js" defer type="module"></script>
</body>
</html