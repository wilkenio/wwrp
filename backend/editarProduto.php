<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
</head>
<body>
<?php
session_start();
require 'connectDatabase/connectDB.php'; 


if (!isset($_SESSION['adm'])) {
    header("Location: ../pages/loginAdm.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'], $_POST['nome_produto'], $_POST['breve_descricao'], $_POST['valor'])) {
    $id_produto = $_POST['id_produto'];
    $nome_produto = $_POST['nome_produto'];
    $breve_descricao = $_POST['breve_descricao'];
    $valor = $_POST['valor'];


    if (isset($_FILES['novo_produto_imagem']) && $_FILES['novo_produto_imagem']['error'] === UPLOAD_ERR_OK) {

        $target_dir = "imgProdutos/";
        $target_file = $target_dir . basename($_FILES["novo_produto_imagem"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;


        $check = getimagesize($_FILES["novo_produto_imagem"]["tmp_name"]);
        if($check === false) {
            echo "O arquivo não é uma imagem válida.";
            $uploadOk = 0;
        }

        if ($_FILES["novo_produto_imagem"]["size"] > 6000000) { // 6MB
            echo "Desculpe, seu arquivo é muito grande.";
            echo "<a href='../pages/editaProduto.php?id=$id_produto'>Voltar para a página de Edição</a>";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Desculpe, aceitamos apenas arquivos JPG, JPEG, PNG & GIF.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Desculpe, seu arquivo não foi enviado.";
            echo "<a href='../pages/editaProduto.php?id=$id_produto'>Voltar para a página de Edição</a>";
        } else {

            if (move_uploaded_file($_FILES["novo_produto_imagem"]["tmp_name"], $target_file)) {
                echo "O arquivo ". basename( $_FILES["novo_produto_imagem"]["name"]). " foi carregado.";

                $sql = "UPDATE produtos SET nome_produto = :nome_produto, breve_descricao = :breve_descricao, valor = :valor, imagem_url = :imagem_url WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nome_produto', $nome_produto);
                $stmt->bindParam(':breve_descricao', $breve_descricao);
                $stmt->bindParam(':valor', $valor);
                $stmt->bindParam(':imagem_url', $target_file);
                $stmt->bindParam(':id', $id_produto);

                if ($stmt->execute()) {
                    header("Location: ../index.php#novidades");
                    exit();
                } else {
                    echo "Erro ao atualizar o produto: " . $stmt->errorInfo()[2];
                }
            } else {
                echo "Desculpe, ocorreu um erro ao enviar seu arquivo.";
                echo "<a href='../pages/editarProduto.php?id=$id_produto'>Voltar para a página de Edição</a>";
            }
        }
    } else {

        $sql = "UPDATE produtos SET nome_produto = :nome_produto, breve_descricao = :breve_descricao, valor = :valor WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome_produto', $nome_produto);
        $stmt->bindParam(':breve_descricao', $breve_descricao);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':id', $id_produto);

        if ($stmt->execute()) {
            header("Location: ../index.php#novidades");
            exit();
        } else {
            echo "Erro ao atualizar o produto: " . $stmt->errorInfo()[2];
        }
    }
} else {
    echo "Requisição inválida.";
}
?>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 75vh;
        background-color: #303030;
        color: white;
        font-size: 1.2rem;
        padding: 5%;
        flex-direction: column;
    }
    a {
        text-decoration: none;
        color: #CFA343;
    }
    a:hover {
        opacity: 0.5;
    }
</style>
</body>
</html>
