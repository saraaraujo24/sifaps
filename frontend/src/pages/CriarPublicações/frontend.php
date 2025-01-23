<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Home/style.css"/>
    <link rel="stylesheet" href="../Home/dropdown.css"/>
    <title>Publicações</title>
<?php
include 'db.php'; // Inclua sua conexão com o banco de dados aqui

// Verifica se a conexão foi estabelecida
if (!$conn) {
    die('Erro ao conectar ao banco de dados.');
}

// Buscar publicações ordenadas pela data de criação
$sql = "SELECT * FROM publicacoes ORDER BY data_criacao DESC";
$result = $conn->query($sql);

// Verifica se houve erro na consulta SQL
if (!$result) {
    die('Erro na consulta SQL: ' . $conn->error);
}
?>

    <style>
        .section-perfilCriado {
           
            height: auto; /* Altura máxima da seção (ajuste conforme necessário) */
           
        }
        .container-posts{
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 25px ;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }
        .publicacao {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .publicacao h2 {
            font-size: 1.4em;
            color: #333;
            text-align: center;
            margin-bottom: 15px;
        }
        .publicacao p {
            font-size: 1.0em;
            color: #555;
            line-height: 1.6;
            text-align: justify;
        }
        .publicacao img {
            max-width: 60%;
            height: auto;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: block;
            margin-left: auto;
            margin-right: auto;
       
        }
        .data-postagem {
            font-size: 0.9em;
            color: #888;
            text-align: left;
            margin-bottom: 10px;
        }
        .autor-postagem {
            font-size: 0.9em;
            color: #555;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- inicio do menu-->
<div id="box">
        <p id="output"></p>
    </div>
      <div class="background-verde">
        <header>
            <div class="menu">
                <nav>
                        <ul class="ul" id="menu-list">
                            <li><a href="../Home/index.html">Home</a></li>
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropbtn">Saúde Mental</a>
                                <div class="dropdown-content">
                                    <a href="../SaudeMental/saude-mental.html"><h5>O Que é Saúde Mental?</h5></a>
                                    <a href="../ComoseCuidar/como-se-cuidar.html"><h5>Como Se Cuidar?</h5></a>
                                </div>
                            </li> 
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropbtn">Publicações</a>
                                <div class="dropdown-content">
                                    <a href="../Videos/videos.html"><h5>Videos</h5></a>
                                    <a href="../CriarPublicações/frontend.php"><h5>Posts</h5></a>
                                    <a href="../Publicacoes/publicacoes.html"><h5>Livros</h5></a>

                                </div>
                            </li>
                            <li><a href="../comentarios/index.php">Comentários</a></li>
                            <li><a href="../meu_projeto/visualizar.php">Profissionais</a></li>
                     
                            <li><a href="../Login/index.html"><img src="../../img/user.png"></a></li>
                        
                        </ul>              
                    <div class="menu-icon">
                        <img src="../../img/menu.png" alt="menu">
                    </div>
                </nav>
            </div>

        </header>
    <div class="faixa-horizontal"></div>
</div>
<!-- fim-->
<section class="section-perfilCriado">
<div class="container-posts">
   

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="publicacao">
                <h2><?php echo htmlspecialchars($row['titulo']); ?></h2>
               
             
                <p><?php echo nl2br(htmlspecialchars($row['texto'])); ?></p>
                <!-- Exibindo imagem se houver -->
                <?php if ($row['imagem']): ?>
                    <?php
                        // Caminho da imagem
                        $imagemPath = $row['imagem'];
                    ?>
                    <img src="<?php echo $imagemPath; ?>" alt="Imagem da publicação">
                <?php endif; ?>

                <!-- Exibindo o nome e o CRP/CRM do profissional -->
                <div class="autor-postagem">
                    <p>Postado por: <?php echo htmlspecialchars($row['nome']); ?> (CRP/CRM: <?php echo htmlspecialchars($row['crp_crm']); ?>)</p>
                </div>
                   <!-- Exibindo data e hora -->
                <div class="data-postagem">
                    <?php
                    // Formatar a data de criação (assumindo que o formato no banco é 'Y-m-d H:i:s')
                    $dataCriacao = new DateTime($row['data_criacao']);
                    echo "Postado em " . $dataCriacao->format('d/m/Y \à\s H:i');
                    ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Nenhuma publicação encontrada.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>
</section>
<br></br><br></br><br></br><br></br><br></br><br></br>
<footer>
    <div class="container">
    <ul>
        <h3 class="colorido">SIFAPS</h3><!-- REbeca 23/10/2024-->
        <p>
            Sifaps é uma forma de oferecer apoio e informação para familiares de pessoas com transtornos.<!-- REbeca 23/10/2024-->
             Reconhecemos os desafios emocionais e práticos que essas famílias enfrentam e queremos ser uma fonte de recursos e orientação.
             Acreditamos que, ao promover a compreensão e a empatia, podemos fortalecer o suporte a esses indivíduos. 
            No Sifaps, buscamos criar um espaço seguro e acolhedor, onde todos possam encontrar amor, esperança e ferramentas para enfrentar essa jornada.</p>
        <div class="redes-sociais">
            <img src="../../img/facebook.png" alt="Facebook">
            <img src="../../img/twitter.png" alt="X">
            <img src="../../img/instagram.png" alt="Instagram">
            <img src="../../img/linkedin.png" alt="Linkedin">
        </div>
    </ul>
    <ul>
        <h3>Links</h3>
        <li><a href="#">Home</a></li><!-- REbeca 23/10/2024-->
        <li><a href="#">Saúde Mental</a></li>
        <li><a href="#">Publicações</a></li>
        <li><a href="#">Videos</a></li>
        <li><a href="#">Livros</a></li>
    </ul>
    <ul>
        <h3>FAQ</h3>
        <li><a href="#">Como funciona</a></li>
        <li><a href="#">Caraccteristicas</a></li>
        <li><a href="#">Contato</a></li>
        <li><a href="#">Sobre</a></li>
        <li><a href="#">Comunicando</a></li>
    </ul>
    <ul>
        <h3>Nos Contate</h3>
        <li><p>(35)997317199</p></li>
        <li><p>email@email.com</p></li>
        <li><p>BRASIL</p></li>
    </ul>
</footer>
<script src="./publicacao.js"></script>
<script src="../Home/main.js"></script>
<script src="../Menu/ocultar-item-menu.js"></script>
</body>
</html>
