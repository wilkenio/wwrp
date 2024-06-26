<!DOCTYPE html>
<html lang="Pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
</head>
<body>
    <?php
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

    <form action="../backend/pagamento.php?id=<?php echo $id_produto; ?>" method="post">
        <img id="img-edit" width="10%" src="../backend/<?php echo $imagem_url; ?>" alt="">
        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">


        <h3><?php echo $nome_produto; ?></h3>
        <h5><?php echo $breve_descricao; ?></h5>
        <b><?php echo $valor; ?></b>
       
        <input type="number" placeholder="Qtd" min="1" value="1" name="qtd">
        <textarea placeholder="Endereço" required name="endereco"></textarea>
        <button type="submit">Gerar Pix</button>
    </form>
</body>
</html>
