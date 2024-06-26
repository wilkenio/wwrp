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
require 'connectDatabase/connectDb.php'; 

// Verificar se há uma sessão de administrador ativa
if (!isset($_SESSION['adm'])) {
    header("Location: ../pages/loginAdm.php");
    exit();
}

// Verificar se o método de requisição é POST e se todos os campos necessários estão presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'], $_POST['nome_produto'], $_POST['breve_descricao'], $_POST['valor'])) {
    $id_produto = $_POST['id_produto'];
    $nome_produto = $_POST['nome_produto'];
    $breve_descricao = $_POST['breve_descricao'];
    $valor = $_POST['valor'];

    // Verificar se um novo arquivo de imagem foi enviado
    if (isset($_FILES['novo_produto_imagem']) && $_FILES['novo_produto_imagem']['error'] === UPLOAD_ERR_OK) {
        // Diretório onde as imagens são armazenadas
        $target_dir = "imgProdutos/";
        $target_file = $target_dir . basename($_FILES["novo_produto_imagem"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Verifica se a imagem é real ou fake
        $check = getimagesize($_FILES["novo_produto_imagem"]["tmp_name"]);
        if($check === false) {
            echo "O arquivo não é uma imagem válida.";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo
        if ($_FILES["novo_produto_imagem"]["size"] > 500000) { // 500KB
            echo "Desculpe, seu arquivo é muito grande.";
            echo "<a href='../pages/editarProduto.php?id=$id_produto'>Voltar para a página de Edição</a>";
            $uploadOk = 0;
        }

        // Permite apenas certos formatos de arquivo
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Desculpe, aceitamos apenas arquivos JPG, JPEG, PNG & GIF.";
            $uploadOk = 0;
        }

        // Verifica se o uploadOk está configurado como 0 por algum erro
        if ($uploadOk == 0) {
            echo "Desculpe, seu arquivo não foi enviado.";
            echo "<a href='../pages/editarProduto.php?id=$id_produto'>Voltar para a página de Edição</a>";
        } else {
            // Move o arquivo para o diretório de destino
            if (move_uploaded_file($_FILES["novo_produto_imagem"]["tmp_name"], $target_file)) {
                echo "O arquivo ". basename( $_FILES["novo_produto_imagem"]["name"]). " foi carregado.";

                // Atualiza os dados no banco de dados com a nova imagem
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
        // Caso nenhum arquivo de imagem tenha sido enviado, atualiza apenas os outros campos no banco de dados
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
