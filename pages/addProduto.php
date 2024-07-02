<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Produto</title>
    <link rel="stylesheet" href="../css/addProduto.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        <h1><i class="bi bi-box"></i> Adicionar Produto</h1>
        <input onchange="alterouImagem()" id="file" type="file" name="produto_imagem" required>
        <label id="fileInput"  for="file"><i class="bi bi-cloud-arrow-up"></i> Carregar Imagem</label>
        <input type="text" name="nome_produto" placeholder="Nome Produto" required>
        <input type="text" name="breve_descricao" placeholder="Breve Descrição" required>
        <input type="number" name="valor"  step="0.01" placeholder="Valor" required>
        <button id="addProduto" type="submit">Adicionar Produto <i class="bi bi-plus-circle"></i></button>
        <a href="../index.php">Voltar para o Site</a>
    </form>
    
    <script>
        function alterouImagem(){
            document.getElementById('fileInput').innerText="Arquivo Carregado";
        }
    </script>
</body>
</html>
