<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="publicacoes.css">
    <link rel="stylesheet" href="../Home/style.css">
    <title>ALÉM DO SOFRIMENTO INDIVIDUAL</title>
</head>
<style>
    .nav-perfil{
        padding: 15px 0;
    }
</style>
<body>
    <!-- início do menu-->
    <div id="box">
        <p id="output"></p>
    </div>
    <div>
        <header>
            <div class="menu">
                <nav class="nav-perfil">
                    <ul class="ul" id="menu-list">
                        <li><a href="../Home/index.html">Página inicial</a></li>
                        <li><a href="../Perfil/perfil.html">Home</a></li>
                        <li><a href="../CriarPublicações/index.php">Criar Publicações</a></li>
                        <li><a href="../meu_projeto/index.html">Criar Perfil</a></li>
                        <li><a href="../Seus-Dados/seus-dados.html">Seus Dados</a></li>
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

    <main>
    <section id="formulario">
        <h2 class="h2-criarPubli">Adicionar Publicação</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="texto" placeholder="Texto" required></textarea>
            <input type="file" name="imagem" accept="image/*" required>
            <button type="submit">Adicionar</button>
        </form>
    </section>

    <section id="publicacoes">
    <?php
        include 'db.php'; // Inclui o arquivo de conexão

        // Verifica se a conexão foi estabelecida
        if (!$conn) {
            die("Erro ao conectar ao banco de dados.");
        }

        // Inicializa variáveis
        $titulo = '';
        $texto = '';
        $nome = ''; // Adicionando o nome do usuário
        $crp_crm = ''; // Adicionando o campo CRP/CRM

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = isset($_POST['titulo']) ? $conn->real_escape_string($_POST['titulo']) : '';
            $texto = isset($_POST['texto']) ? $conn->real_escape_string($_POST['texto']) : '';

            // Captura o nome do usuário e CRP/CRM do localStorage através do JS
            $nome = isset($_POST['nome']) ? $conn->real_escape_string($_POST['nome']) : '';
            $crp_crm = isset($_POST['crp_crm']) ? $conn->real_escape_string($_POST['crp_crm']) : '';

            // Processar imagem
            $imagem = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $imagem = 'uploads/' . basename($_FILES['imagem']['name']);
                if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem)) {
                    echo "Erro ao mover a imagem para o diretório de uploads.";
                }
            }

            // Verifica se a publicação já existe
            $sql_check = "SELECT * FROM publicacoes WHERE titulo = '$titulo' AND texto = '$texto'";
            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows === 0) {
                // Inserção no banco de dados com o campo 'nome' e 'crp_crm'
                $sql = "INSERT INTO publicacoes (titulo, texto, imagem, nome, crp_crm) VALUES ('$titulo', '$texto', '$imagem', '$nome', '$crp_crm')";
                
                // Depuração de SQL

                if ($conn->query($sql) === TRUE) {
                    echo "Publicação adicionada com sucesso!";
                } else {
                    echo "Erro ao adicionar publicação: " . $conn->error;
                }
            }
        }

        // Selecionar publicações, ordenadas pela data de criação mais recente
        $sql = "SELECT * FROM publicacoes ORDER BY data_criacao DESC";
        $result = $conn->query($sql);

        // Verificar se houve erro na consulta SQL
        if (!$result) {
            die('Erro na consulta SQL: ' . $conn->error);
        }

        // Exibir publicações
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='publicacao'>";
                echo "<h2>{$row['titulo']}</h2>";
                echo "<p>{$row['texto']}</p>";
                if ($row['imagem']) {
                    echo "<center><img src='{$row['imagem']}' alt='Imagem' /></center>";
                }
                echo "<p><strong>Publicado por:</strong> {$row['nome']} <br>(CRP/CRM: {$row['crp_crm']})</p>";
                // Adicione o botão para excluir
                echo "<form method='post' action='excluir_publicacao.php' style='margin-top: 10px;'>";
                echo "<input type='hidden' name='id' value='{$row['id']}'>";
                echo "<button type='submit'>Excluir</button>";
                echo "</form>";
                echo "</div>";
            }
            
        } else {
            echo "<p>Nenhuma publicação encontrada.</p>";
        }

        $conn->close();
    ?>
    </section>
    </main>
    </div>
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
    <script src="../Home/main.js"></script>
    <script src="publicacao.js"></script>
   
</body>
</html>
