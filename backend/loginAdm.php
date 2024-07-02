<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<?php 
 include 'connectDatabase/connectDB.php'; 
 session_start(); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $senha = filter_var($_POST['senha'], FILTER_SANITIZE_STRING);
    

    $sql = "SELECT * FROM loginadm WHERE email = :email";
    

    $stmt = $conn->prepare($sql);
    

    $stmt->bindParam(':email', $email);
    

    $stmt->execute();
    

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    

    if ($user && password_verify($senha, $user['senha'])) {
        header("Location: ../index.php");
        $_SESSION['adm'] = $email;


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