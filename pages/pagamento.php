<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
<link rel="stylesheet" href="../css/pagamento.css">
    <script>
        // Função para verificar o status do pagamento a cada segundo
        function verificarPagamento(paymentId) {
            setInterval(function () {
                // Fazer uma requisição AJAX para verificar o status do pagamento
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {

                        if (xhr.status === 200) {
                            console.log(xhr.responseText)
                            // Verificar se o pagamento foi feito
                            if (xhr.responseText === 'approved') {
                                document.getElementById('aprovado').style.display="flex";

                                setInterval(function () {           
                                    window.location.href = "../index.php";
                                 }, 3000);
                               // alert('Pagamento foi realizado com sucesso!');
                                // Pode adicionar mais ações aqui, como redirecionamento ou exibição de mais informações
                            }
                        } else {
                            console.error('Erro ao verificar pagamento');
                        }
                    }
                };
                xhr.open('GET', '../backend/verificar_pagamento.php?payment_id=' + paymentId, true);
                xhr.send();
            }, 1000); // Verifica a cada 1 segundo (1000 milissegundos)
        }
    </script>
</head>
<div id="aprovado">✔</div>
<body>
    <?php
    if (isset($_GET['payment_id'])) {
        $payment_id = $_GET['payment_id'];

        require '../backend/connectDatabase/connectDB.php';

        // Consulta na tabela pagamentos para obter id_produto, qrcode_url e quantidade
        $sql = "SELECT id_produto, qr_code_base64, copy_paste, qtd FROM pagamentos WHERE payment_id = :payment_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_produto = $row['id_produto'];
            $qrcode_url = $row['qr_code_base64'];
            $pix_code = $row['copy_paste'];
            $quantidade = $row['qtd'];

            // Consulta na tabela produtos para obter informações do produto
            $sql_produto = "SELECT * FROM produtos WHERE id = :id_produto";
            $stmt_produto = $conn->prepare($sql_produto);
            $stmt_produto->bindParam(':id_produto', $id_produto);
            $stmt_produto->execute();

            if ($stmt_produto->rowCount() > 0) {
                $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);
                $nome_produto = $produto['nome_produto'];
                $breve_descricao = $produto['breve_descricao'];
                $valor = $produto['valor'];
                $imagem_url = $produto['imagem_url'];

                // Calcular o valor total baseado na quantidade
                $valor_total = $valor * $quantidade;

                // Exibir as informações do produto e QR Code
                ?>
                <h1>Pague</h1>
                <form action="../backend/gerar_pix.php" method="post">
                    <img id="img-edit" width="10%" src="../backend/<?php echo $imagem_url; ?>" alt="">
                    <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                    <h3><?php echo $nome_produto; ?></h3>
                    <h5><?php echo $breve_descricao; ?></h5>
                    <b>Valor unitário: R$ <?php echo number_format($valor, 2, ',', '.'); ?></b><br>
                    <b>Quantidade: <?php echo $quantidade; ?></b><br>
                    <b>Valor total: R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></b><br>
                </form>

                <h2>QR Code para pagamento:</h2>
                <img width="10%" src="data:image/png;base64,<?php echo $qrcode_url; ?>" alt="QR Code">

                <h2>Copie o código Pix:</h2>
                <input value="<?php echo $pix_code; ?>">
                <button>Copiar</button>

                <script>
                    // Iniciar verificação do pagamento ao carregar a página
                    verificarPagamento(<?php echo $payment_id; ?>);
                </script>

                <?php
            } else {
                echo "Produto não encontrado.";
            }
        } else {
            echo "Pagamento não encontrado.";
        }
    } else {
        echo "Parâmetro payment_id não especificado.";
    }
    ?>
</body>

</html>