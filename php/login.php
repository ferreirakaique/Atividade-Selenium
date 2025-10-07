<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha_usuario'])) {
            session_start();
            $_SESSION['id_usuario']   = $usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
            $_SESSION['email_usuario'] = $usuario['email_usuario'];
            $login_sucesso = true;
        } else {
            $senha_incorreta = true;
        }
    } else {
        $usuario_nao_encontrado = true;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h2 class="titulo">Login | SILENIUM</h2>
        <form action="" method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Entrar</button>

            <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </div>

    <?php if (isset($login_sucesso) && $login_sucesso): ?>
        <script>
            Swal.fire({
                title: "Sucesso!",
                text: "Login realizado com sucesso!",
                icon: "success",
                confirmButtonColor: "#6A0DAD"
            }).then(() => {
                window.location.href = "index.php";
            });
        </script>
    <?php elseif (isset($senha_incorreta) && $senha_incorreta): ?>
        <script>
            Swal.fire({
                title: "Senha incorreta!",
                text: "Verifique sua senha e tente novamente.",
                icon: "error",
                confirmButtonColor: "#6A0DAD"
            });
        </script>
    <?php elseif (isset($usuario_nao_encontrado) && $usuario_nao_encontrado): ?>
        <script>
            Swal.fire({
                title: "Usuário não encontrado!",
                text: "Nenhuma conta foi encontrada com esse e-mail.",
                icon: "warning",
                confirmButtonColor: "#6A0DAD"
            });
        </script>
    <?php endif; ?>

</body>

</html>