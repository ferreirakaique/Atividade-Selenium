<?php
include 'conexao.php';
session_start();

$login_sucesso = false;
$senha_incorreta = false;
$usuario_nao_encontrado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $usuario_nao_encontrado = true;
    } else {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email_usuario = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            if (password_verify($senha, $usuario['senha_usuario'])) {
                $_SESSION['id_usuario']    = $usuario['id_usuario'];
                $_SESSION['nome_usuario']  = $usuario['nome_usuario'];
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
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Selenium</title>
    <link rel="stylesheet" href="login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h2 class="titulo">Login | Selenium</h2>
        <form action="" method="POST" id="form-login">
            <label>Email:</label>
            <input type="email" name="email" id="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit" id="btn-login">Entrar</button>

            <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </div>

    <?php if ($login_sucesso): ?>
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
    <?php elseif ($senha_incorreta): ?>
        <script>
            Swal.fire({
                title: "Senha incorreta!",
                text: "Verifique sua senha e tente novamente.",
                icon: "error",
                confirmButtonColor: "#6A0DAD"
            });
        </script>
    <?php elseif ($usuario_nao_encontrado): ?>
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