<?php
session_start();
require 'connectDatabase/connectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    try {

        $stmt = $conn->prepare("SELECT `status` FROM pagamentos WHERE payment_id = :payment_id");
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $status_atual = $row['status'];

            // Verificar o status do pagamento utilizando a API do Mercado Pago
            $url = "https://api.mercadopago.com/v1/payments/$payment_id";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer APP_USR-909053041483213-010316-3c1ea708956b75fef31c61f30bc4fb5b-394928107",
                "Content-Type: application/json",
            ]);

            $paymentResponse = curl_exec($ch);
            $payment = json_decode($paymentResponse, true);
            curl_close($ch);

            if (!$payment || isset($payment['error'])) {
                echo "Erro ao obter status do pagamento: " . $payment['message'];
                exit;
            }

            $novo_status = $payment['status'];

 
            if ($status_atual !== $novo_status) {
                // Atualiza o status no banco de dados
                $stmt_update = $conn->prepare("UPDATE pagamentos SET `status` = :novo_status WHERE payment_id = :payment_id");
                $stmt_update->bindParam(':novo_status', $novo_status);
                $stmt_update->bindParam(':payment_id', $payment_id);
                $stmt_update->execute();

                // Deu bom
                if ($stmt_update->rowCount() > 0) {
                    echo "Status atualizado com sucesso: $novo_status";
                } else {
                    echo "Falha ao atualizar o status no banco de dados.";
                }
            } else {
                echo "$novo_status";
            }

            exit;
        } else {
            echo "Pagamento não encontrado na base de dados.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro ao buscar status do pagamento: " . $e->getMessage();
        exit;
    }
} else {
    echo "Parâmetros inválidos!";
    exit;
}
?>
