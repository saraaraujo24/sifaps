<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profissionais Cadastrados</title>
    <link rel="stylesheet" href="config.css">
    <link rel="stylesheet" href="../Home/style.css"/>
    <link rel="stylesheet" href="../Home/dropdown.css"/>
</head>
<style>
    .section-visualizar {   
        height: 120vh; /* Altura máxima da seção (ajuste conforme necessário) */    
    }
    p .img-whats {
        width: 30px;
        height: auto;
        vertical-align: middle;
    }
</style>
<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Consulta para obter os dados dos profissionais
$sql = "SELECT * FROM profissionais";
$stmt = $pdo->query($sql);
$profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
<div id="box">
        <p id="output"></p>
    </div>
      <div >
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

    <section>
        <h1>Profissionais Cadastrados</h1>

        <div class="container-visualizar">
            <?php foreach ($profissionais as $profissional): ?>
                <div class="profissional-card">
                    <img src="uploads/<?php echo htmlspecialchars($profissional['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($profissional['nome']); ?>">
                    <h2><?php echo htmlspecialchars($profissional['nome']); ?></h2><br>
                    <p><strong>Profissão:</strong> <?php echo htmlspecialchars($profissional['profissao']); ?>   
                    <strong>CRP:</strong><?php echo htmlspecialchars($profissional['crp_crm']); ?></p>
                    <p><strong>Email:</strong>  <?php echo htmlspecialchars($profissional['email']); ?></p>
                    <p ><img class="img-whats" src="../../img/whatsapp.png"> <?php echo htmlspecialchars($profissional['celular']); ?> </p>
                    <p class="descricao"><?php echo nl2br(htmlspecialchars($profissional['descricao'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

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
        </div>
    </footer>
    <script src="../Menu/ocultar-item-menu.js"></script>
    <script src="../Home/main.js"></script>
</body>

</html>
