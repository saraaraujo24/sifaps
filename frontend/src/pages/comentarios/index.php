<!DOCTYPE html>
<html lang="pt-BR">
<head>
<?php

include 'db.php';
session_start();

$usuarioLogado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
$mensagemErro = isset($_SESSION['mensagem_erro']) ? $_SESSION['mensagem_erro'] : null;
unset($_SESSION['mensagem_erro']); // Limpe a mensagem após exibi-la

$tipoUsuario = isset($usuarioLogado['tipo_usuario']) ? $usuarioLogado['tipo_usuario'] : null; // Supondo que você tenha o tipo do usuário na sessão
$usuarioIdLogado = isset($usuarioLogado['id']) ? $usuarioLogado['id'] : null; // ID do usuário logado

// Obter comentários
$stmt = $pdo->query("SELECT * FROM comentarios WHERE resposta_id IS NULL ORDER BY data_criacao DESC");
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <link rel="stylesheet" href="./index.css"/>
    <link rel="stylesheet" href="../Home/style.css"/>
    <link rel="stylesheet" href="../Home/dropdown.css"/>

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
    <section>
        <div class="section_main-imgC">
            <div class="main-imgC">
                <img src="fig11.png" alt="comentarios" />
            </div>
        </div>
    </section>
    <div class="containerComentariopai">
        <div class="containerComentario">
            <h1 class="tituloComentario">Comentários</h1>
                <form name="formComentario" method="POST" action="processar_comentario.php">
                    <textarea name="comentario" required placeholder="Digite seu comentário..."></textarea>
                    <input type="hidden" name="usuario_id" > <!-- Campo oculto para o ID do usuário -->
                    <button type="submit">Adicionar Comentário</button>
                </form>
        
                <ul>
<?php foreach ($comentarios as $comentario): ?>
    <li id="comentario-<?= $comentario['id'] ?>">
        <strong class="nome_usuario">
            <?= ($tipoUsuario === 'profissional') ? htmlspecialchars($comentario['nome']) : htmlspecialchars($comentario['nome_usuario'])  ?>
           CRP: <?php echo htmlspecialchars($comentario['crp_crm']);?>
        </strong><br>
        <?= htmlspecialchars($comentario['comentario']) ?>
        <strong class="nome_usuario">

        <!-- Verifique se o usuário logado pode excluir o comentário -->
        <script>
            // Obtenha as informações do usuário logado do localStorage
            const usuarioLogado = JSON.parse(localStorage.getItem('usuario'));

            // Verifique se o usuarioLogado existe e se o ID do comentário é igual ao do usuário logado
            if (usuarioLogado && usuarioLogado.id === <?= $comentario['usuario_id'] ?>) {
                // Exibe o botão de excluir apenas para o autor do comentário
                const excluirBtn = document.createElement('button');
                excluirBtn.className = 'btn-excluir';
             
                excluirBtn.textContent = 'Excluir';

                excluirBtn.onclick = function() {
                    excluirComentario(<?= $comentario['id'] ?>, document.getElementById('comentario-<?= $comentario['id'] ?>'));
                };

                document.getElementById('comentario-<?= $comentario['id'] ?>').appendChild(excluirBtn);
            }
  

        </script>

        <ul id="respostas-<?= $comentario['id'] ?>" class="respostas"></ul>
        <form name="formResposta" method="POST" action="processar_comentario.php" style="display:inline;" class="formResposta">
            <input type="hidden" name="resposta_id" value="<?= $comentario['id'] ?>">
            <textarea name="resposta" required placeholder="Digite sua resposta..."></textarea>
            <li>
                <a href="#" 
                class="btn-responder" 
                onclick="enviarResposta(event, this.closest('form'))">
                    Responder
                </a>
            </li>
        </form>
        <li onclick="toggleRespostas(<?= $comentario['id'] ?>, this)">
            <a href="#" class="btn-ver-respostas">Ver mais</a>
        </li>
    </li>
<?php endforeach; ?>
</ul>



        </div>
    </div>
<br></br><br></br><br></br><br></br>
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


<script src="comentario.js"></script>
<script src="../Home/main.js"></script>
<script src="../Menu/ocultar-item-menu.js"></script>
</html>
