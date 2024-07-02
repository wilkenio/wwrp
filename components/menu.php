<div class="navbar">
    <div class="logo">
        <a href="index.php">
            <img src="./images/logo.png" width="125px" alt="Logo da loja">
        </a>
    </div>
    <nav>
        <ul id="MenuItems">
            <li><a id="item-menu-produto" href="index.php">Produtos</a></li>


            <?php
            session_start(); // Certifique-se de iniciar a sessão
            if (isset($_SESSION['adm'])) {
                echo '
                        <li class="menu-adm" title="Apenas adm"><a href="pages/addProduto.php">Adicionar Produto </a></li>
                        <li class="menu-adm" title="Apenas adm"><a href="pages/vendas.php">Vendas</a></li>
                        <li class="menu-adm" title="Apenas adm"><a href="backend/logout.php">Sair de ADM</a></li>
                    ';
            }
            ?>
        </ul>
    </nav>
    <img src="./images/menu.png" class="menu-icon" onclick="menutoggle()">
</div>
</div>

        <!---->