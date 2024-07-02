<?php

$servername = "127.0.0.1"; // Nome do servidor
$username = "fourdevs_will"; // Nome de usuário do banco de dados
$password = "lxIcuGvj5Wn$"; // Senha do banco de dados
$dbname = "fourdevs_wwrp"; // Nome do banco de dados

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexão bem-sucedida";
} catch(PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}
?>
