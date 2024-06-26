<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../css/editaproduto.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php

session_start();
if (!isset($_SESSION['adm'])) {
    header("Location: ../pages/loginAdm.php");
    exit();
}
    // Verifique se há um ID de produto válido na URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id_produto = $_GET['id'];

        // Incluir o arquivo de conexão com o banco de dados
        require '../backend/connectDatabase/connectDB.php';

        // Query para buscar o produto específico pelo ID
        $sql = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_produto);
        $stmt->execute();

        // Verificar se encontrou o produto
        if ($stmt->rowCount() > 0) {
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            $nome_produto = $produto['nome_produto'];
            $breve_descricao = $produto['breve_descricao'];
            $valor = $produto['valor'];
            $imagem_url = $produto['imagem_url'];
        } else {
            // Se não encontrou o produto, redirecionar ou exibir uma mensagem de erro
            header("Location: ../index.php");
            exit();
        }
    } else {
        // Se não houver ID de produto válido na URL, redirecionar ou exibir uma mensagem de erro
        header("Location: ../index.php");
        exit();
    }
    ?>

    <form action="../backend/editarProduto.php" method="post" enctype="multipart/form-data">
        <h1><i class="bi bi-pencil-fill"></i> Editar Produto </h1>
        <img id="img-edit"  src="../backend/<?php echo $imagem_url; ?>" alt="">
        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
        <input id="edit-img-file" type="file" name="novo_produto_imagem" onchange="alterouImagem()" >
        <label id="upload-edit-file" for="edit-img-file"><i class="bi bi-cloud-arrow-up"></i> Carregar Imagem</label>
        <p>Nome do Produto:</p>
        <input type="text" name="nome_produto" placeholder="Nome Produto" value="<?php echo $nome_produto; ?>" required>
        <p>Descrição:</p>
        <input type="text" name="breve_descricao" placeholder="Breve Descrição" value="<?php echo $breve_descricao; ?>" required>
        <p>Valor:</p>
        <input type="number" name="valor" placeholder="Valor" value="<?php echo $valor; ?>" required>
        <button type="submit" id="btt-edit">Salvar Alterações <i class="bi bi-pencil"></i></button>
        <a href="../index.php">Voltar para o Site</a>
    </form>


    <script>
        function alterouImagem(){
            document.getElementById('img-edit').style.display="none";
        }
    </script>
</body>
</html>
