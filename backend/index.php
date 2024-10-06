<?php
$dbnome = "sifaps";
$conecao = mysqli_connect('localhost', 'root', '') or die("Erro de conexão");

// Criação do banco de dados
$criadb = mysqli_query($conecao, "CREATE DATABASE IF NOT EXISTS $dbnome");
mysqli_select_db($conecao, $dbnome) or die("Erro ao selecionar o banco de dados");

// Criação da tabela admin
$criacaoUsuarios = "CREATE TABLE IF NOT EXISTS admin (
    idlogin INT(11) AUTO_INCREMENT PRIMARY KEY,
    crp_crm VARCHAR(100) NOT NULL,
    login VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,  -- Adicionado UNIQUE para garantir unicidade
    senha VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
if (mysqli_query($conecao, $criacaoUsuarios)) {
    echo "Tabela admin criada.<br>";
} else {
    echo "Erro ao criar tabela admin: " . mysqli_error($conecao) . "<br>";
}

// Criação da tabela cadastro
$criacao1 = "CREATE TABLE IF NOT EXISTS cadastroProf (
    idProfissional INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) DEFAULT NULL,
    cpf VARCHAR(50) NOT NULL,
    celular VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    crp_crm VARCHAR(50) NOT NULL,
    profissao VARCHAR(50) NOT NULL,
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,  -- Novo campo para data
    PRIMARY KEY (idProfissional)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

if (mysqli_query($conecao, $criacao1)) {
    echo "Tabela cadastro criada.<br>";
} else {
    echo "Erro ao criar tabela cadastro: " . mysqli_error($conecao) . "<br>";
}


// Criação da tabela cadastro
$criacao1 = "CREATE TABLE IF NOT EXISTS cadastroUser (
    idUser INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) DEFAULT NULL,
    cpf VARCHAR(50) NOT NULL,
    celular VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    cidade VARCHAR(50) NOT NULL,
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,  -- Novo campo para data
    PRIMARY KEY (idUser)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
if (mysqli_query($conecao, $criacao1)) {
    echo "Tabela cadastro criada.<br>";
} else {
    echo "Erro ao criar tabela cadastro: " . mysqli_error($conecao) . "<br>";
}

// Criação da tabela comentario
$criacao2 = "CREATE TABLE IF NOT EXISTS comentario (
    idcomentario INT(11) AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    idusuario INT(11),
    FOREIGN KEY (idusuario) REFERENCES admin(idlogin)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
if (mysqli_query($conecao, $criacao2)) {
    echo "Tabela comentario criada.<br>";
} else {
    echo "Erro ao criar tabela comentario: " . mysqli_error($conecao) . "<br>";
}

// Criação da tabela resposta
$criacao3 = "CREATE TABLE IF NOT EXISTS resposta (
    idresposta INT(11) AUTO_INCREMENT PRIMARY KEY,
    idcomentario INT(11),
    flex ENUM('p<5') NOT NULL,
    texto TEXT NOT NULL,
    FOREIGN KEY (idcomentario) REFERENCES comentario(idcomentario)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
if (mysqli_query($conecao, $criacao3)) {
    echo "Tabela resposta criada.<br>";
} else {
    echo "Erro ao criar tabela resposta: " . mysqli_error($conecao) . "<br>";
}

// Dados a serem inseridos
$crp_crm = '123456';
$login = 'Admin';
$email = 'admin@admin.com';
$senha = '123456';

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
    $inserirUser = "INSERT INTO admin (crp_crm,login, email, senha) VALUES (?, ?, ?, ?)";
    $stmt = $conecao->prepare($inserirUser);
    $stmt->bind_param("ssss", $crp_crm, $login, $email, $senha);
    if ($stmt->execute()) {
        echo "Dados inseridos com sucesso na tabela admin.<br>";
    } else {
        echo "Erro ao inserir dados: " . $stmt->error . "<br>";
    }

}

// Fecha a declaração e a conexão
$stmt->close();
$conecao->close();
?>

//ALTER TABLE cadastroProf ADD COLUMN status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente';
