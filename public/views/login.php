<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie/Rejestracja</title>
    <link rel="stylesheet" href="public/styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .welcome-overlay {
            position: fixed;
            z-index: 9999;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle, #3498db 0%, #2c3e50 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #fff;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            opacity: 1;
            transition: opacity 1s ease;
        }
        .welcome-logo {
            font-size: 4rem;
            margin-bottom: 30px;
            animation: pop 1s cubic-bezier(.68,-0.55,.27,1.55) 1;
        }
        @keyframes pop {
            0% { transform: scale(0.2) rotate(-30deg); opacity: 0; }
            60% { transform: scale(1.2) rotate(10deg); opacity: 1; }
            80% { transform: scale(0.95) rotate(-5deg);}
            100% { transform: scale(1) rotate(0);}
        }
        .welcome-text {
            animation: fadeInUp 1.2s 0.5s both;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(40px);}
            100% { opacity: 1; transform: translateY(0);}
        }
        .welcome-overlay.hide {
            opacity: 0;
            pointer-events: none;
        }
        .confetti {
            position: fixed;
            pointer-events: none;
            z-index: 10000;
            top: 0; left: 0; width: 100vw; height: 100vh;
        }
    </style>
    <script>
    function createConfettiPiece() {
        const confetti = document.createElement('div');
        confetti.className = 'confetti-piece';
        const size = Math.random() * 8 + 8;
        confetti.style.position = 'absolute';
        confetti.style.width = size + 'px';
        confetti.style.height = size + 'px';
        confetti.style.background = `hsl(${Math.random()*360}, 70%, 60%)`;
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.top = '-20px';
        confetti.style.opacity = Math.random() * 0.7 + 0.3;
        confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
        confetti.style.transform = `rotate(${Math.random()*360}deg)`;
        confetti.style.transition = 'top 2.2s cubic-bezier(.68,-0.55,.27,1.55), transform 2.2s';
        return confetti;
    }
    function launchConfetti() {
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'confetti';
        document.body.appendChild(confettiContainer);
        for(let i=0; i<60; i++) {
            const piece = createConfettiPiece();
            confettiContainer.appendChild(piece);
            setTimeout(() => {
                piece.style.top = '100vh';
                piece.style.transform += ` scale(${Math.random()*0.7+0.7}) rotate(${Math.random()*720-360}deg)`;
            }, 10 + Math.random()*300);
        }
        setTimeout(() => confettiContainer.remove(), 2500);
    }
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.createElement('div');
        overlay.className = 'welcome-overlay';
        overlay.innerHTML = `
            <div class="welcome-logo">🍸</div>
            <div class="welcome-text">Witamy w <span style="color:#ffe066;">DrinkAdvisor</span>!</div>
        `;
        document.body.appendChild(overlay);
        setTimeout(() => {
            overlay.classList.add('hide');
            launchConfetti();
        }, 1800);
        setTimeout(() => {
            overlay.remove();
        }, 2800);
    });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>DrinkAdvisor</h1>
        </header>
        <div class="auth-box">
            <h2>Logowanie</h2>
            <form action="login" method="POST">
                <input type="text" name="login" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <button type="submit">Zaloguj się</button>
            </form>
            <p>Nie masz konta? <a href="register">Zarejestruj się</a></p>
            <br>
            <h3>
                <?php if(isset($message)) {
                    foreach ($message as $msg){
                        echo $msg; 
                    }
                }?>
            </h3>

            <?php if(isset($_SESSION['user'])): ?>
                <a href="logout">Wyloguj się</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>