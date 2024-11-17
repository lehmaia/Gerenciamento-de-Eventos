<?php
include("database/conn.php");

// Obter o ID do usuário da URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit;
}

$foto = !empty($usuario['foto']) ? $usuario['foto'] : 'Fotos/default.png';

// Atualizar informações no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $conn->real_escape_string($_POST['nome']);
    $novoEmail = $conn->real_escape_string($_POST['email']);
    $novoTelefone = $conn->real_escape_string($_POST['telefone']);

    // Atualizar a foto se um novo arquivo foi enviado
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $diretorio = 'Fotos/';
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0755, true);
        }
        $nomeArquivo = basename($_FILES['foto']['name']);
        $caminhoFoto = $diretorio . uniqid() . '-' . $nomeArquivo;
        move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFoto);
    } else {
        $caminhoFoto = $foto;
    }

    $stmt = $conn->prepare("UPDATE usuario SET nome = ?, email = ?, telefone = ?, foto = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $novoNome, $novoEmail, $novoTelefone, $caminhoFoto, $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="Perfil.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .edit-input {margin-bottom: 10px; display: none; }
        .fechar{
            visibility: hidden;
            opacity: 60%;
            transform: translatex(-260px);
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" enctype="multipart/form-data" id="formAlteracoes">
            <div class="form">
            <div class="form-image">
                <label for="uploadFoto">
                    <img id="foto-preview" src="<?php echo $foto; ?>" alt="Foto de Perfil">
                </label>
                <input type="file" id="uploadFoto" name="foto" accept="image/*" onchange="previewImagem(this)">
            </div>

            <script>
                let alterado = false;
                function previewImagem(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('foto-preview').src = e.target.result;
                            alterado = true;
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function habilitarEdicao() {
                    document.querySelectorAll('.edit-input').forEach(input => input.style.display = 'block');
                    document.querySelectorAll('.readonly').forEach(label => label.style.display = 'none');
                    document.querySelectorAll('.fechar').forEach(fechar => {fechar.style.visibility = 'visible';});
                    alterado = true;
                }

                function fecharEdicao() {
                    // Adiciona a classe para esconder o formulário
                    document.querySelectorAll('.edit-input').forEach(input => input.style.display = 'none');
                    document.querySelectorAll('.readonly').forEach(label => label.style.display = 'block');
                    document.querySelectorAll('.fechar').forEach(fechar => {fechar.style.visibility = 'hidden';});
                    alterado = false;
                }
            </script>
            <div class="form-conteudo">
                <div class="form-header">
                    <div class="titulo">
                        <h1>Editar Perfil</h1>
                    </div>
                    <a class="caneta" onclick="habilitarEdicao()"><i class="bx bx-pencil"></i></a>
                    <div class="returnbutton">
                        <button><a href="javascript:history.back()">Voltar</a></button>
                        <a class="fechar" onclick="fecharEdicao()"><i class="bx bx-x"></i></a>
                    </div>
                </div>

                <div class="dados-container">
                    <div class="dados">
                        <label for="nome"><strong>Nome: </strong></label>
                        <span class="readonly"><?php echo htmlspecialchars($usuario['nome']); ?></span>
                        <input type="text" id="nome" name="nome" class="edit-input" value="<?php echo htmlspecialchars($usuario['nome']); ?>">
                    </div>

                    <div class="dados">
                        <label for="email"><strong>E-mail: </strong></label>
                        <span class="readonly"><?php echo htmlspecialchars($usuario['email']); ?></span>
                        <input type="email" id="email" name="email" class="edit-input" value="<?php echo htmlspecialchars($usuario['email']); ?>">
                    </div>

                    <div class="dados">
                        <label for="telefone"><strong>Telefone: </strong></label>
                        <span class="readonly"><?php echo htmlspecialchars($usuario['telefone']); ?></span>
                        <input type="text" id="telefone" name="telefone" class="edit-input" value="<?php echo htmlspecialchars($usuario['telefone']); ?>">
                    </div>

                    <div class="dados">
                        <label for="senha" class="readonly"><strong>Senha:</strong></label>
                        <span class="readonly">********</span>
                        <a href="RedefinirSenha.php?id=<?php echo $id ?>" class="readonly"><i class="bx bx-pencil"></i></a>
                    </div>

                    <div class="exclui-button">
                        <button type="button" onclick="confirmDelete()">Excluir Conta <i class="bx bx-trash"></i></button>
                    </div>
                </div>

                <div class="salvar-button">
                    <button type="submit" onclick="salvarAlteracoes()">Salvar Alterações</button>
                </div>
            </div>
            </div>
        </form>

        <script>
            function salvarAlteracoes() {
                alert("Alterações salvas com sucesso!");
                alterado = false;
            }

            function confirmDelete() {
                if (confirm("Tem certeza que deseja excluir sua conta?")) {
                    window.location.href = "deleteAccount.php?id=<?php echo $id ?>";
                }
            }

            // Aviso ao tentar sair da página sem salvar
            window.addEventListener("beforeunload", function(event) {
                if (alterado) {
                    event.preventDefault();
                    event.returnValue = "Você tem alterações não salvas. Deseja realmente sair?";
                }
            });
        </script>
    </div>
</body>
</html>
