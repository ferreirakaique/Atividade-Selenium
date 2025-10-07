<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome  = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();
    $cadastro_sucesso = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h2 class="titulo">Cadastro | SILENIUM</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Cadastrar</button>

            <p>Já tem conta? <a href="login.php">Faça login</a></p>
        </form>
    </div>

    <?php if (isset($cadastro_sucesso) && $cadastro_sucesso): ?>
        <script>
            Swal.fire({
                title: "Sucesso!",
                text: "Cadastro realizado com sucesso!",
                icon: "success",
                confirmButtonColor: "#6A0DAD"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php";
                }
            });
        </script>
    <?php endif; ?>
    <?php if (isset($cadastro_erro) && $cadastro_erro): ?>
        <script>
            Swal.fire({
                title: "Erro no cadastro",
                text: "Verifique as informações e tente novamente",
                icon: "error",
                confirmButtonColor: "#6A0DAD"
            });
        </script>
    <?php endif; ?>
</body>

</html>