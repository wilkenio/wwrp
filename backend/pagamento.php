<?php
session_start();
require 'connectDatabase/connectDB.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_produto']) && isset($_POST['qtd']) && isset($_POST['endereco'])) {
    $id_produto = $_POST['id_produto'];
    $qtd = $_POST['qtd'];
    $endereco = $_POST['endereco'];
    gerarPixEPersistir($id_produto, $qtd, $endereco);
} else {
    echo "Dados inválidos!";
    exit;
}

function gerarPixEPersistir($id_produto, $qtd, $endereco) {
    global $conn;

    try {
        $stmt = $conn->prepare("SELECT nome_produto, breve_descricao, valor FROM produtos WHERE id = :id_produto");
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) {
            echo "Produto não encontrado!";
            exit;
        }

        $amount = (float)$produto['valor'] * (int)$qtd;

    } catch (PDOException $e) {
        echo "Erro ao buscar produto: " . $e->getMessage();
        exit;
    }

    $data = [
        "transaction_amount" => $amount,
        "description" => $produto['breve_descricao'],
        "payment_method_id" => "pix",
        "payer" => [
            "email" => "wil@gmail.com",
            "identification" => ["type" => "cpf", "number" => "06502626700"],
            "address" => [],
        ],
    ];

    $url = "https://api.mercadopago.com/v1/payments";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: SEU TOKEN",
        "Content-Type: application/json",
    ]);

    $paymentResponse = curl_exec($ch);
    $payment = json_decode($paymentResponse, true);
    curl_close($ch);

    if (!$payment || isset($payment['error'])) {
        echo "Erro ao processar pagamento: " . $payment['message'];
        exit;
    }

    if (!isset($payment["id"])) {
        echo "ID do pagamento não encontrado na resposta da API.";
        exit;
    }

    $payment_id = $payment["id"];
    $qr_code_base64 = $payment["point_of_interaction"]["transaction_data"]["qr_code_base64"] ?? null;
    $pix_code = $payment["point_of_interaction"]["transaction_data"]["qr_code"] ?? null;
    $status = $payment["status"];

    try {
        $stmt = $conn->prepare("INSERT INTO pagamentos (payment_id, copy_paste, qr_code_base64, `status`, transaction_amount, id_produto, qtd, endereco) VALUES (:payment_id, :copy_paste, :qr_code_base64, :status, :transaction_amount, :id_produto, :qtd, :endereco)");
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->bindParam(':copy_paste', $pix_code);
        $stmt->bindParam(':qr_code_base64', $qr_code_base64);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':transaction_amount', $amount);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->bindParam(':qtd', $qtd);
        $stmt->bindParam(':endereco', $endereco);

        $stmt->execute();

    // Após inserir os dados no banco de dados com sucesso
    header("Location: ../pages/pagamento.php?payment_id=$payment_id");


        exit;

    } catch (PDOException $e) {
        echo "Erro ao inserir dados no banco: " . $e->getMessage();
    }
    echo "PIX criado com sucesso!";
    exit;
}
?>
