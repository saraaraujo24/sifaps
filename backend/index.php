<?php
// Nome do banco de dados
$dbnome = "sifaps";
$conecao = mysqli_connect('localhost', 'root', '') or die("Erro de conexão");

// Criação do banco de dados, caso não exista
$criadb = mysqli_query($conecao, "CREATE DATABASE IF NOT EXISTS $dbnome");
if (!$criadb) {
    die("Erro ao criar banco de dados: " . mysqli_error($conecao));
}

// Seleciona o banco de dados sifaps
mysqli_select_db($conecao, $dbnome) or die("Erro ao selecionar o banco de dados sifaps");

// Criação da tabela admin
$criacaoUsuarios = "CREATE TABLE IF NOT EXISTS admin (
    idlogin INT(11) AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

if (mysqli_query($conecao, $criacaoUsuarios)) {
    echo "Tabela admin criada.<br>";
} else {
    echo "Erro ao criar tabela admin: " . mysqli_error($conecao) . "<br>";
}

// Criação da tabela usuarios
$comentario_user = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_usuario ENUM('profissional', 'usuario') NOT NULL,
    nome VARCHAR(255) NOT NULL,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    celular VARCHAR(15) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    estado VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    crp_crm VARCHAR(50) NULL,  -- Permite valores nulos
    profissao VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conecao, $comentario_user)) {
    echo "Tabela usuarios criada.<br>";
} else {
    echo "Erro ao criar tabela usuarios: " . mysqli_error($conecao) . "<br>";
}

// Criação da tabela comentarios
$coment = "CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    comentario TEXT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resposta_id INT DEFAULT NULL,
    nome_usuario VARCHAR(100) NOT NULL,
    crp_crm VARCHAR(255) NULL,  -- Adicionando o campo 'crp_crm'
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (resposta_id) REFERENCES comentarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

if (mysqli_query($conecao, $coment)) {
    echo "Tabela comentarios criada.<br>";
} else {
    echo "Erro ao criar tabela comentarios: " . mysqli_error($conecao) . "<br>";
}

// Criação da tabela resposta
$criacao3 = "CREATE TABLE IF NOT EXISTS resposta (
    idresposta INT(11) AUTO_INCREMENT PRIMARY KEY,
    idcomentario INT(11),
    flex ENUM('p<5') NOT NULL,
    texto TEXT NOT NULL,
    FOREIGN KEY (idcomentario) REFERENCES comentarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

if (mysqli_query($conecao, $criacao3)) {
    echo "Tabela resposta criada.<br>";
} else {
    echo "Erro ao criar tabela resposta: " . mysqli_error($conecao) . "<br>";
}

// Verifica se a coluna 'status' já existe na tabela 'usuarios'
$verificaColuna = "SHOW COLUMNS FROM usuarios LIKE 'status'";
$resultado = mysqli_query($conecao, $verificaColuna);

if (mysqli_num_rows($resultado) == 0) {
    // A coluna 'status' não existe, então adiciona
    $alterTable = "ALTER TABLE usuarios ADD COLUMN status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente'";
    if (mysqli_query($conecao, $alterTable)) {
        echo "Coluna status adicionada à tabela usuarios.<br>";
    } else {
        echo "Erro ao adicionar coluna status: " . mysqli_error($conecao) . "<br>";
    }
} else {
    echo "A coluna 'status' já existe na tabela usuarios.<br>";
}

// Dados a serem inseridos
$login = 'Admin';
$email = 'admin@admin.com';
$senha = password_hash('123456', PASSWORD_DEFAULT); // Use hash para senhas
$crp_crm = ''; // Valor vazio para a coluna 'crp_crm', já que agora ela pode ser NULL

// Verifica se o email já existe
$verificaEmail = "SELECT idlogin FROM admin WHERE email = ?";
$stmt = $conecao->prepare($verificaEmail);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "O email já está cadastrado.<br>";
} else {
    // Inserção de dados
    $inserirUser = "INSERT INTO admin (login, email, senha) VALUES (?, ?, ?)";
    $stmt = $conecao->prepare($inserirUser);
    $stmt->bind_param("sss", $login, $email, $senha);
    if ($stmt->execute()) {
        echo "Dados inseridos com sucesso na tabela admin.<br>";
    } else {
        echo "Erro ao inserir dados: " . $stmt->error . "<br>";
    }
}

// Criação da tabela publicacoes MOISES
$criacao4 = "CREATE TABLE IF NOT EXISTS publicacoes (
    id INT(11) NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    texto TEXT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    imagem VARCHAR(255) DEFAULT NULL,
    nome VARCHAR(255) NOT NULL,  -- Adicionando o campo 'nome'
    crp_crm VARCHAR(255) NOT NULL,  -- Adicionando o campo 'crp_crm'
    PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";

if (mysqli_query($conecao, $criacao4)) {
    echo "Tabela publicacoes criada.<br>";
} else {
    echo "Erro ao criar tabela publicacoes: " . mysqli_error($conecao) . "<br>";
}


// Criação da tabela profissionais
$criacaoProfissionais = "CREATE TABLE IF NOT EXISTS profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profissao VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    celular VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    crp_crm VARCHAR(50) NOT NULL,
    foto VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conecao, $criacaoProfissionais)) {
    echo "Tabela profissionais criada no banco cadastro_profissional.<br>";
} else {
    echo "Erro ao criar tabela profissionais: " . mysqli_error($conecao) . "<br>";
}

// Fecha a declaração e a conexão
$stmt->close();
$conecao->close();
?>
