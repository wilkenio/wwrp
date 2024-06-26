<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Pagamentos</title>
    <!-- Adicionar link para o CSS do Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-approved {
            background-color: rgb(164, 255, 164) !important;
            /* Cor de fundo para pagamentos aprovados */
        }
    </style>
</head>

<body>
    <?php require '../backend/connectDatabase/connectDB.php';
    // Verificar se há uma sessão de administrador ativa
    session_start();
    if (!isset($_SESSION['adm'])) {
        header("Location: ../pages/loginAdm.php");
        exit();
    }
    ?>

    <div class="container">
        <h2>Visualizar Pagamentos</h2>
        <a href="../index.php">Voltar para o site</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                    <th>Status</th>
                    <th>Data de Pagamento</th>
                    <th>Endereço</th>
                    <!-- Adicione outras colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Query para buscar todos os pagamentos com nome do produto correspondente, ordenados pela data de pagamento decrescente
                $sql = "SELECT p.id, pr.nome_produto, p.status, p.created_at, p.endereco
        FROM pagamentos p 
        INNER JOIN produtos pr ON p.id_produto = pr.id
        ORDER BY p.created_at DESC"; // Ordena pela data de pagamento em ordem decrescente
                $stmt = $conn->query($sql);

                // Loop para exibir os pagamentos
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $statusClass = ''; // Classe para destacar o status
                
                    // Adicionar classe de destaque se o pagamento estiver aprovado
                    if ($row['status'] == 'approved') {
                        $statusClass = 'bg-approved';
                    }
                    ?>
                    <tr class="<?php echo $statusClass; ?>">
                        <td><?php echo $row['nome_produto']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['endereco']; ?></td>
                        <!-- Adicione outras colunas conforme necessário -->
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>

</body>

</html>