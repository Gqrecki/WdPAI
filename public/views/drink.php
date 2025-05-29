<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wódka Żubrówka</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/styles/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>DrinkAdvisor</h1>
            <nav>
                <a href="home">Strona Główna</a>
                <a href="user">Profil</a>
                <a href="search">Znajdź znajomych</a>
                <a href="admin">Panel Admina</a>
            </nav>
        </header>
        <div class="drink-details">
            <div class="drink-info">
                <h2>Wódka Żubrówka</h2>
                <p>Objętość: 0.5L</p>
                <p>Zawartość alkoholu: 40%</p>
                <p>Cena oscylacyjna: 35-45 zł</p>
                <p>Średnia ocena: 4.5/5</p>
                <button>Dodaj do ulubionych</button>
            </div>
            <div class="drink-rating">
                <h3>Twoja ocena</h3>
                <form>
                    <select name="rating" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <textarea placeholder="Twój komentarz"></textarea>
                    <button type="submit">Wyślij</button>
                </form>
            </div>
            <div class="drink-comments">
                <h3>Komentarze</h3>
                <p><strong>Janek:</strong> Świetny smak, polecam! (Ocena: 4/5)</p>
                <p><strong>Anna:</strong> Zbyt intensywna trawa. (Ocena: 3/5)</p>
            </div>
        </div>
    </div>
</body>
</html>