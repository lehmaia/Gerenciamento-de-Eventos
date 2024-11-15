<?php
include("database/conn.php");

// Obter o ID do usuário da URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT nome, email, foto FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if ($usuario):
    $foto = !empty($usuario['foto']) ? $usuario['foto'] : 'Fotos/default.png';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <style>
        img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>
    <h2>Perfil de <?php echo htmlspecialchars($usuario['nome']); ?></h2>
    <img src="<?php echo $foto; ?>" alt="Foto de Perfil"><br>
    <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
</body>
</html>
<?php else: ?>
    <p>Usuário não encontrado.</p>
<?php endif; ?>
