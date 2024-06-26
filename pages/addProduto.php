<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Produto</title>
    <link rel="stylesheet" href="../css/addProduto.css">
</head>
<body>

<?php 
    session_start();
    if (!isset($_SESSION['adm'])) {
        header("Location: ../pages/loginAdm.php");
        exit();
    }
?>
    <form action="../backend/addProduto.php" method="post" enctype="multipart/form-data">
        <h1>Add Produto</h1>
        <input type="file" name="produto_imagem" required>
        <input type="text" name="nome_produto" placeholder="Nome Produto" required>
        <input type="text" name="breve_descricao" placeholder="Breve Descrição" required>
        <input type="number" name="valor" min="0.1" placeholder="Valor" required>
        <button type="submit">Adicionar Produto</button>
        <a href="../index.php">Voltar para o Site</a>
    </form>
</body>
</html>
