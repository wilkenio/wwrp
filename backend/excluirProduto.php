<?php
session_start();
require 'connectDatabase/connectDB.php'; 

// Verificar se há uma sessão de administrador ativa
if (!isset($_SESSION['adm'])) {
    header("Location: ../pages/loginAdm.php");
    exit();
}

// Verificar se o método de requisição é POST e se o campo 'id_produto' está presente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id_produto = $_POST['id_produto'];

    // Query para buscar a imagem do produto para deletar o arquivo da imagem
    $sql = "SELECT imagem_url FROM produtos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_produto);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagem_url = $produto['imagem_url'];

        // Deletar o arquivo de imagem se existir
        if (file_exists($imagem_url)) {
            unlink($imagem_url);
        }

        // Query para excluir o produto do banco de dados
        $sql = "DELETE FROM produtos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_produto);

        if ($stmt->execute()) {
            header("Location: ../index.php#novidades");
            exit();
        } else {
            echo "Erro ao excluir o produto: " . $stmt->errorInfo()[2];
        }
    } else {
        echo "Produto não encontrado.";
    }
} else {
    echo "Requisição inválida.";
}
?>
