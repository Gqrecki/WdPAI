<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona Główna - DrinkAdvisor</title>
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
        <div class="sort-section">
            <label for="sort">Sortuj według:</label>
            <select id="sort" name="sort">
                <option value="trending" selected>Trendujące</option>
                <option value="best">Najlepsze</option>
                <option value="worst">Najgorsze</option>
                <option value="expensive">Najdroższe</option>
                <option value="cheap">Najtańsze</option>
            </select>
        </div>
        <div class="grid">
            <div class="drink-card">
                <h2>Wódka Żubrówka</h2>
                <p>Ocena: 4.5/5</p>
                <p>Cena: 40 zł</p>
                <a href="drink.html">Szczegóły</a>
            </div>
            <div class="drink-card">
                <h2>Whisky Jack Daniel's</h2>
                <p>Ocena: 4.8/5</p>
                <p>Cena: 120 zł</p>
                <a href="drink.html">Szczegóły</a>
            </div>
            <div class="drink-card">
                <h2>Rum Captain Morgan</h2>
                <p>Ocena: 4.2/5</p>
                <p>Cena: 80 zł</p>
                <a href="drink.html">Szczegóły</a>
            </div>
        </div>
    </div>
</body>
</html>