<?php
// Inicia a sessão
session_start();

// Destrói todas as variáveis de sessão
$_SESSION = array();

// Se você quiser destruir completamente a sessão, também deve destruir o cookie da sessão.
// Nota: Isso destruirá a sessão, não apenas os dados da sessão!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão
session_destroy();

// Redireciona para a página de login ou outra página desejada
header("Location: ../pages/loginAdm.php");
exit;
?>
