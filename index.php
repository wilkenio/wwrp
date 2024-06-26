<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WWRPR Esportes | Loja Virtual </title>
    <link rel="shortcut icon" type="image/jpg" href="./images/favicon.png" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/d652b9f554.js" crossorigin="anonymous"></script>
</head>

<body>

    <div id="menu" class="container">
        <?php include 'components/menu.php'; ?>
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                        <h1>Encontre <br>seu novo manto!</h1>
                        <p>Sucesso nem sempre é sobre grandeza. é sobre consistência. consistir em trabalho duro gera
                            sucesso. Com isso a grandeza virá.
                        </p>
                        <a href="#maisVendidos" class="btn">Veja Mais &#8594;</a>
                    </div>
                    <div class="col-2">
                        <img src="./images/image1.png">
                    </div>
                </div>
            </div>
        </div>

        <div id="maisvendidos" class="small-container">
            <h2 class="title">Mais Vendidos ⭐</h2>
            <div class="row">
                <?php
                // Include database connection file
                require 'backend/connectDatabase/connectDB.php';
                // Verificar se é um administrador (substitua com sua lógica de verificação)
                $isAdmin = isset($_SESSION['adm']) && $_SESSION['adm'] == true;
                // Query para buscar os quatro produtos mais vendidos com base nos pagamentos aprovados
                $sql = "SELECT p.*, COUNT(pg.id_produto) AS total_vendas 
                FROM produtos p 
                INNER JOIN pagamentos pg ON p.id = pg.id_produto 
                WHERE pg.status = 'approved'
                GROUP BY p.id
                ORDER BY total_vendas DESC
                LIMIT 4";

                $stmt = $conn->query($sql);

                // Loop através dos resultados do banco de dados
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_produto = $row['id'];
                    $nome_produto = $row['nome_produto'];
                    $breve_descricao = $row['breve_descricao'];
                    $valor = $row['valor'];
                    $imagem_url = $row['imagem_url'];
                    ?>
                    <div class="col-4">
                        <img src="backend/<?php echo $imagem_url; ?>">
                        <h4><?php echo $nome_produto; ?></h4>
                        <div class="rating">
                            <i class="fas fa-star-half-alt"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <p><?php echo $breve_descricao; ?></p>
                        <b><?php echo "R$ " . $valor; ?></b>
                        <a href="pages/produto.php?id=<?php echo $id_produto; ?>"><button
                                class="comprar">Comprar</button></a>
                        <?php
                        // Mostrar o botão de edição se for administrador
                        if ($isAdmin) {
                            ?>
                            <a href="pages/editaProduto.php?id=<?php echo $id_produto; ?>">
                                <button class="editar">Editar</button>
                            </a>
                            <a href="pages/excluirProduto.php?id=<?php echo $id_produto; ?>">
                                <button class="editar">Excluir</button>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                <?php } // Fechando o loop ?>
            </div>
        </div>


        <div id="novidades" class="small-container">
            <h2 class="title">Novidades 🎉</h2>
            <div class="row">
                <?php
                // Include database connection file
                require 'backend/connectDatabase/connectDB.php';

                // Query para buscar todos os produtos
                $sql = "SELECT * FROM produtos";
                $stmt = $conn->query($sql);

                // Loop através dos resultados do banco de dados
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_produto = $row['id'];
                    $nome_produto = $row['nome_produto'];
                    $breve_descricao = $row['breve_descricao'];
                    $valor = $row['valor'];
                    $imagem_url = $row['imagem_url'];

                    // Verificar se é um administrador (substitua com sua lógica de verificação)
                    $isAdmin = isset($_SESSION['adm']) && $_SESSION['adm'] == true;

                    ?>
                    <div class="col-4">
                        <img src="backend/<?php echo $imagem_url; ?>">
                        <h4><?php echo $nome_produto; ?></h4>
                        <div class="rating">
                            <i class="fas fa-star-half-alt"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                        <p><?php echo $breve_descricao; ?></p>
                        <b><?php echo "R$ " . $valor; ?></b>
                        <a href="pages/produto.php?id=<?php echo $id_produto; ?>"><button
                                class="comprar">Comprar</button></a>
                        <?php
                        // Mostrar o botão de edição se for administrador
                        if ($isAdmin) {
                            ?>
                            <a href="pages/editaProduto.php?id=<?php echo $id_produto; ?>">
                                <button class="editar">Editar</button>
                            </a>
                            <a href="pages/excluirProduto.php?id=<?php echo $id_produto; ?>">
                                <button class="editar">Excluir</button>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                <?php } // Fechando o loop ?>
            </div>
        </div>


        <div class="testimonial">
            <div class="small-container">
                <div class="row">
                    <div class="col-3">
                        <i class="fa fa-quote-left"></i>
                        <p>Até hoje, para cada não que recebo, vou atrás de um sim!</p>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <img src="./images/user-1.WEBP">
                        <h3>Cafu</h3>
                    </div>
                    <div class="col-3">
                        <i class="fa fa-quote-left"></i>
                        <p>A oportunidade, ela não bate em sua porta. É preciso se dedicar e se esforçar porque ela
                            chega no seu colo.</p>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <img src="./images/user-2.WEBP">
                        <h3>Tite</h3>
                    </div>
                    <div class="col-3">
                        <i class="fa fa-quote-left"></i>
                        <p>Sem disciplina, o talento não serve pra nada.</p>
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <img src="./images/user-3.WEBP">
                        <h3>Cristiano Ronaldo</h3>
                    </div>
                </div>
            </div>
        </div>
        <div id="poup-up">
            <div id="imagem-produto"><img src="" alt=""></div>
        </div>

        <script src="./js/script.js"></script>
</body>

</html>