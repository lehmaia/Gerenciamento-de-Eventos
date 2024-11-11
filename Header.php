<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="img/IconeLogo.png" />
    <title>Quartzo Azul</title>
    <link rel="stylesheet" href="Header.css">
</head>
<body>
    <header class="header">
        <!-- Logo e nome da empresa -->
        <div class="logo-container">
            <img src="img/IconeLogo.png" alt="Logo" class="logo">
            <span class="company-name">Quartzo Azul</span>
        </div>

        <!-- Menu de navegação -->
        <nav class="navbar">
            <div class="dropdown">
                <a href="Orcamento.php" class="dropbtn">Orçamento</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Convidados</button>
                <div class="dropdown-content">
                    <a href="#">Lista</a>
                    <a href="#">Convite</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="Agenda.php" class="dropbtn">Agenda</a>
            </div>
            <div class="dropdown">
                <button class="dropbtn">Eventos</button>
                <div class="dropdown-content">
                    <a href="#">Opção 1</a>
                    <a href="#">Opção 2</a>
                    <a href="#">Opção 3</a>
                </div>
            </div>

            <!-- Foto de perfil com dropdown -->
            <div class="profile-dropdown">
                <img src="profile.jpg" alt="Foto de Perfil" class="profile-pic">
                <div class="profile-content">
                    <span class="account-label">Conta</span>
                    <div class="profile-info">
                        <img src="profile.jpg" alt="Foto do Usuário" class="profile-pic-small">
                        <div>
                            <span class="user-name">Leticia Maia</span>
                            <span class="user-email">leticia@quartzoazul.com</span>
                        </div>
                    </div>
                    <hr>
                    <a href="#">Perfil</a>
                    <a href="#">Mudar de Conta</a>
                    <a href="#">Configurações</a>
                    <a href="#">Sair</a>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
