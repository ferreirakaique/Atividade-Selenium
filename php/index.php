<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | SILENIUM</title>
    <link rel="stylesheet" href="../css/inicio.css">

    <!-- Importa o SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <main class="container">
        <h1>👋 Seja bem-vindo, <span><?php echo htmlspecialchars($nome_usuario); ?></span>!</h1>

        <div class="dados-usuario">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome_usuario); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email_usuario); ?></p>
        </div>

        <form id="logoutForm" action="logout.php" method="POST">
            <button type="submit" class="logout-btn">Sair</button>
        </form>
    </main>

    <script>
        // Captura o formulário de logout
        const logoutForm = document.getElementById('logoutForm');

        logoutForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o envio imediato

            Swal.fire({
                title: 'Tem certeza que deseja sair?',
                text: "Você precisará fazer login novamente para voltar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, sair',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Saindo...',
                        text: 'Você será desconectado em instantes.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        logoutForm.submit(); // Envia o formulário após o alerta
                    }, 1500);
                }
            });
        });
    </script>
</body>

</html>