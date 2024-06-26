<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

<?php 
 require 'connectDatabase/connectDB.php'; 
 session_start(); 


// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $senha = filter_var($_POST['senha'], FILTER_SANITIZE_STRING);
    
    // Prepara a query SQL para buscar o usuário pelo email
    $sql = "SELECT * FROM loginAdm WHERE email = :email";
    
    // Prepara a declaração
    $stmt = $conn->prepare($sql);
    
    // Vincula os parâmetros
    $stmt->bindParam(':email', $email);
    
    // Executa a query
    $stmt->execute();
    
    // Busca o usuário
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verifica se o usuário foi encontrado e se a senha está correta
    if ($user && password_verify($senha, $user['senha'])) {
        header("Location: ../index.php");
        $_SESSION['adm'] = $email;

        // Aqui você pode iniciar uma sessão, redirecionar o usuário, etc.
    } else {
        echo "Email ou senha incorretos.";
        echo "<a href='../pages/loginAdm.php'>Voltar para Login ADM</a>";
    }
}
?>
<style>
    body{
        display: flex;
        align-items: center;
        justify-content: center;
        height: 75vh;
        background-color: #303030;
        color: white;
        font-size: 1.2rem;
        padding: 5%;
        flex-direction: column;
    }
    a{
        text-decoration:none;
        color:#CFA343 ;
    }
    a:hover{
        opacity: 0.5;
    }
</style>

</body>
</html>