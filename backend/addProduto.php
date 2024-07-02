<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
</head>
<body>
    

<?php
session_start();
require 'connectDatabase/connectDB.php'; 


if (!isset($_SESSION['adm'])) {
    header("Location: ../pages/loginAdm.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['produto_imagem'])) {

    // Diretório para armazenar as imagens
    $target_dir = "imgProdutos/";
    $target_file = $target_dir . basename($_FILES["produto_imagem"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Verifica se a imagem é real ou fake
    if ($_FILES["produto_imagem"]["tmp_name"]) {
        $check = getimagesize($_FILES["produto_imagem"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    } else {
        echo "No file uploaded or invalid file.";
        $uploadOk = 0;
    }


    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Verifica o tamanho do arquivo
    if ($_FILES["produto_imagem"]["size"] > 6000000) { // botei 6MB tava dando erro em deploy
        echo "Desculpe, seu arquivo é muito grande.😢";
        echo"<a href='../pages/addProduto.php'>◀️ Volotar a página de Adicionar Produto </a>";
        $uploadOk = 0;
    }

    //  formatos de arquivo permitidos
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Desculpe, aceitamos apenas arquivos de formato JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    // Verifica se o uploadOk 
    if ($uploadOk == 0) {
        echo "Desculpe, seu arquivo não foi enviado.😢";
        echo"<a href='../pages/addProduto.php'>◀️ Voltar a página de Adicionar Produto </a>";
    } else {
        if (move_uploaded_file($_FILES["produto_imagem"]["tmp_name"], $target_file)) {
            echo "O arquivo ". basename( $_FILES["produto_imagem"]["name"]). " foi carregado.";

           
            $nome_produto = $conn->quote($_POST['nome_produto']);
            $breve_descricao = $conn->quote($_POST['breve_descricao']);
            $valor = $conn->quote($_POST['valor']);
            $imagem_url = $conn->quote($target_file);

          
            $sql = "INSERT INTO produtos (nome_produto, breve_descricao, valor, imagem_url)
            VALUES ($nome_produto, $breve_descricao, $valor, $imagem_url)";

            if ($conn->exec($sql)) {
                header("Location: ../index.php#novidades");
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->errorInfo()[2];
            }
        } else {
            echo "Desculpe, ocorreu um erro ao enviar seu arquivo. 😢";
            echo"<a href='../pages/addProduto.php'>◀️ Voltar a página de Adicionar Produto </a>";
        }
    }
} else {
    echo "Requisição Inválida, chama Will.";
}
?>
<style>
    body{
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
    a{
        text-decoration:none;
        color:#CFA343 ;
    }
    a:hover{
        opacity: 0.5;
    }
</style>
</body>
</html>
