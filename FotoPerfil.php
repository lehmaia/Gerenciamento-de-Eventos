<?
include_once ("database/conn.php");
//ainda não adicionei o campo de foto do usuário na tabelagit
// Buscar a imagem
$sql = "SELECT foto FROM usuarios WHERE id = $id";
$result = $conn->query($sql);

// Exibir a imagem
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $foto = $row['foto'];
    
    // Enviar cabeçalhos apropriados para exibir a imagem
    header("Content-Type: image/jpeg");
    echo $foto;
} else {
    echo "Imagem não encontrada.";
}
?>