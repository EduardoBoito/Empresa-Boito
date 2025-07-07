<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" a href="style.css">
</head>
<style>body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5; 
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;

    background-size: cover;
    background-position: center;
    color: #333;
}

form {
    background-color: rgba(255, 255, 255, 0.9); 
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 350px;
    text-align: center;
}

form h2 {
    color: #007bff; 
    margin-bottom: 25px;
    font-size: 24px;
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    text-align: left;
    color: #555;
}

form input[type="text"],
form input[type="password"] {
    width: calc(100% - 20px); 
    padding: 12px 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box; 
    font-size: 16px;
}

form input[type="submit"] {
    background-color: #28a745; 
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

form input[type="submit"]:hover {
    background-color: #218838; 
}

p {
    margin-top: 20px;
    font-weight: bold;
}

p.error { 
    color: red;
    font-size: 14px;
    margin-top: 10px;
}</style>
<body>

<?php

// Configurações do banco de dados
$host = 'localhost';
$user = 'root'; // usuário padrão do XAMPP
$password = ''; // senha padrão do XAMPP (vazia)
$database = 'boito'; // substitua pelo nome do seu banco de dados

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Criptografia de senha (apenas para exemplo/criação de usuários)
// echo password_hash(123456, PASSWORD_DEFAULT);

// Receber dados do forms
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Acessar o IF quando o usuario clicar no botão de acesso do formulario
if (!empty($dados["Sendlogin"])) {
    // Preparar a consulta SQL
    $query_usuario = "SELECT id, senha FROM usuarios WHERE usuario = ? LIMIT 1";
    $stmt = $conn->prepare($query_usuario);
    $stmt->bind_param("s", $dados["usuario"]);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows == 1) {
        // Usuário encontrado, verificar senha
        $row_usuario = $resultado->fetch_assoc();
        if (md5($dados["senha_usuario"], $row_usuario['senha'])) {
            // Senha correta - iniciar sessão e redirecionar
            session_start();
            $_SESSION['id'] = $row_usuario['id'];
            $_SESSION['usuario'] = $dados["usuario"];
            
            header("Location: dashboard.php"); // redireciona para página restrita
            exit();
        } else {
            echo "<p style='color: red'>Erro: Senha incorreta!</p>";
        }
    } else {
        echo "<p style='color: red'>Erro: Usuário não encontrado!</p>";
    }
}

?>

<!-- Inicio do formulario -->
<form method="POST" action="">

<label>Usuário: </label>
<input type="text" name="usuario" placeholder="digite o usuário" required><br><br>

<label>Senha: </label>
<input type="password" name="senha_usuario" placeholder="digite a senha" required><br><br>

<input type="submit" name="Sendlogin" value="Acessar">
</form>
<!-- fim do formulario -->



</body>
</html>


