<?php
session_start();
require 'connectDatabase/connectDB.php'; 


if (!isset($_SESSION['adm'])) {
    header("Location: ../pages/loginAdm.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id_produto = $_POST['id_produto'];


    $sql = "SELECT imagem_url FROM produtos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_produto);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        $imagem_url = $produto['imagem_url'];

        if (file_exists($imagem_url)) {
            unlink($imagem_url);
        }


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
