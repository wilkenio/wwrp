<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoginAdm</title>

    <link rel="stylesheet" href="../css/loginAdm.css">
</head>
<body>
<?php 
    session_start(); // Certifique-se de iniciar a sessão
    if(isset($_SESSION['adm'])){
        header("Location: ../index.php");
    }
?>
<form action="../backend/loginAdm.php" method="post">
    <h1>Login Adm</h1>
    <input name="email" type="email" placeholder="E-mail">
    <input name="senha" type="password" placeholder="Senha">
    <input type="submit">
    <a href="../index.php">Ver site</a>
</form>
</body>
</html>