<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
    <link rel="stylesheet" href="../css/pagamento.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        function verificarPagamento(paymentId) {
            setInterval(function () {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {

                        if (xhr.status === 200) {
                            console.log(xhr.responseText)
                          
                            if (xhr.responseText === 'approved') {
                                document.getElementById('aprovado').style.display = "flex";

                                setInterval(function () {
                                    window.location.href = "../index.php";
                                }, 3000);
                            }
                        } else {
                            console.error('Erro ao verificar pagamento');
                        }
                    }
                };
                xhr.open('GET', '../backend/verificar_pagamento.php?payment_id=' + paymentId, true);
                xhr.send();
            }, 1000); 
        }
    </script>
</head>
<div id="aprovado">✔</div>

<body>
    <?php
    if (isset($_GET['payment_id'])) {
        $payment_id = $_GET['payment_id'];

        require '../backend/connectDatabase/connectDB.php';

       
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

             
                $valor_total = $valor * $quantidade;

              
                ?>

                <form action="../backend/gerar_pix.php" method="post">
                    <h1>Pagamento</h1>
                    <h2>QR Code para pagamento:</h2>
                    <img id="qr-code" width="10%" src="data:image/png;base64,<?php echo $qrcode_url; ?>" alt="QR Code">

                    <h3>Copie o código Pix:</h3>
                    <input id="pixCopy" value="<?php echo $pix_code; ?>" readonly>
                    <p id="copiar" onclick="copiar()">Copiar <i class="bi bi-copy"></i></p>
                    <div id="produto-previa">
                        <img id="img-produto" width="10%" src="../backend/<?php echo $imagem_url; ?>" alt="">
                        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
                        <h4><?php echo $nome_produto; ?></h4>
                        <b>Valor unitário: <br> R$ <?php echo number_format($valor, 2, ',', '.'); ?></b><br>
                        <b>Quantidade: <?php echo $quantidade; ?></b><br>
                        <b>Valor total: R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></b><br>
                    </div>
                </form>

                <script>
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

    <script>
        function copiar() {
           
            var copyText = document.getElementById("pixCopy");

          
            copyText.select();
            copyText.setSelectionRange(0, 99999); 

         
            navigator.clipboard.writeText(copyText.value).then(function() {
             
              
            }).catch(function(err) {
            
              
            });
        }
    </script>
</body>

</html>
