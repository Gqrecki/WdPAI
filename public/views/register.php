<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="public/styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1>DrinkAdvisor</h1>
        </header>
        <div class="auth-box">
            <h2>Rejestracja</h2>
            <form action="register" method="POST">
                <input type="text" name="login" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <input type="password" name="password2" placeholder="Powtórz hasło" required>
                <button type="submit">Zarejestruj się</button>
            </form>
            <p>Masz już konto? <a href="login">Zaloguj się</a></p>
            <br>
            <h3>
                <?php if(isset($message)) {
                    foreach ($message as $msg){
                        echo $msg; 
                    }
                }?>
            </h3>
        </div>
    </div>
</body>
</html>
