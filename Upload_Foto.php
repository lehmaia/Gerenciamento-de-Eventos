<?php
include("database/conn.php");
session_start();

if (!isset($_GET['id'])) {
    die("ID de usuário inválido.");
}

$user_id = (int)$_GET['id'];

// Processar o upload da foto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extensao, $permitidos)) {
            die("Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.");
        }

        // Gerar um novo nome para a imagem
        $novoNome = uniqid('perfil_', true) . '.' . $extensao;
        $caminhoDestino = "Fotos/" . $novoNome;

        // Mover o arquivo para a pasta 'uploads'
        if (move_uploaded_file($foto['tmp_name'], $caminhoDestino)) {
            // Atualizar o caminho da imagem no banco de dados
            $sql = "UPDATE usuario SET foto = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $caminhoDestino, $user_id);
            $stmt->execute();

            // Redirecionar para a página de login
            header("Location: Login.php?");
            exit;
        } else {
            echo "Erro ao fazer o upload.";
        }
    } else {
        echo "Nenhuma foto selecionada.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Upload de Foto de Perfil</title>
    <link rel="stylesheet" href="Foto.css">
</head>
<body>
    <div class="container">
        <h2>Envie sua Foto de Perfil</h2>
        <form action="Upload_foto.php?id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
            <img id="preview" src="uploads/default.png" alt="Prévia da Imagem">
            <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImagem()" required><br>
            <button type="submit">Salvar Foto</button>
        </form>
    </div>

    <script>
        function previewImagem() {
            const input = document.getElementById('foto');
            const preview = document.getElementById('preview');
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
