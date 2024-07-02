<!DOCTYPE html>
<html lang="Pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
    <link rel="stylesheet" href="../css/produto.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id_produto = $_GET['id'];

        require '../backend/connectDatabase/connectDB.php';

        $sql = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_produto);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            $nome_produto = $produto['nome_produto'];
            $breve_descricao = $produto['breve_descricao'];
            $valor = $produto['valor'];
            $imagem_url = $produto['imagem_url'];
        } else {
            header("Location: ../index.php");
            exit();
        }
    } else {
        header("Location: ../index.php");
        exit();
    }
    ?>

    <form action="../backend/pagamento.php?id=<?php echo $id_produto; ?>" method="post">
        <img id="img-edit" width="10%" src="../backend/<?php echo $imagem_url; ?>" alt="">
        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">

        <div>
            <h3><?php echo $nome_produto; ?></h3>
            <h5><?php echo $breve_descricao; ?></h5>
            <b id="preco">R$ <?php echo $valor; ?></b>
            <p>Endereço:</p>
            <textarea placeholder="Endereço" required name="endereco"></textarea>
            <p>Quantidade:</p>
            <input type="number" onchange="preco(this)" placeholder="Qtd" min="0.10" step="0.01" value="1" name="qtd">

            <button id="gerapix" type="submit">Gerar Pix <i class="bi bi-bag-fill"></i></button>
        </div>
    </form>

    <script>
        function preco(element) {
            let qtd = element.value;
            let preco = <?php echo $valor; ?>;

            let total = (qtd * preco).toFixed(2);

            document.getElementById("preco").innerText="R$"+total;
        }

    </script>
</body>

</html>