<?php
include 'conexao.php';

$cadastro_sucesso = false;
$cadastro_erro = false;
$mensagem_erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Valida campos
    if (empty($nome) || empty($email) || empty($senha)) {
        $cadastro_erro = true;
        $mensagem_erro = "Todos os campos devem ser preenchidos.";
    } else {
        // Verifica se já existe o nome ou email
        $verifica = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = ? OR nome_usuario = ?");
        $verifica->bind_param("ss", $email, $nome);
        $verifica->execute();
        $resultado = $verifica->get_result();

        if ($resultado->num_rows > 0) {
            $cadastro_erro = true;
            $mensagem_erro = "Nome ou e-mail já cadastrados.";
        } else {
            // Cadastro
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $senha_hash);

            if ($stmt->execute()) {
                $cadastro_sucesso = true;
            } else {
                $cadastro_erro = true;
                $mensagem_erro = "Erro no cadastro. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Selenium</title>
    <link rel="stylesheet" href="cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h2 class="titulo">Cadastro | Selenium</h2>
        <form action="" method="POST" id="form-cadastro">
            <label>Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label>Email:</label>
            <input type="email" id="email" name="email" required>

            <label>Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" id="btn-cadastrar">Cadastrar</button>

            <p>Já tem conta? <a href="login.php">Faça login</a></p>
        </form>
    </div>

    <?php if ($cadastro_sucesso): ?>
        <script>
            Swal.fire({
                title: "Sucesso!",
                text: "Cadastro realizado com sucesso!",
                icon: "success",
                confirmButtonColor: "#6A0DAD"
            }).then(() => {
                window.location.href = "login.php";
            });
        </script>
    <?php elseif ($cadastro_erro): ?>
        <script>
            Swal.fire({
                title: "Erro no cadastro",
                text: "<?= $mensagem_erro ?>",
                icon: "error",
                confirmButtonColor: "#6A0DAD"
            });
        </script>
    <?php endif; ?>
</body>

</html>