<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
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
        <div class="admin-panel">
            <div class="admin-grid">
                <div class="admin-module">
                    <h2>Dodaj trunek</h2>
                    <form>
                        <input type="text" placeholder="Nazwa trunku" required>
                        <input type="text" placeholder="Objętość (np. 0.5L)" required>
                        <input type="text" placeholder="Zawartość alkoholu (np. 40%)" required>
                        <input type="text" placeholder="Cena oscylacyjna (np. 35-45 zł)" required>
                        <textarea placeholder="Opis trunku"></textarea>
                        <button type="submit">Dodaj</button>
                    </form>
                </div>
                <div class="admin-module">
                    <h2>Edytuj trunek</h2>
                    <form>
                        <select name="drink">
                            <option value="zubrowka">Wódka Żubrówka</option>
                            <option value="jack">Whisky Jack Daniel's</option>
                            <option value="morgan">Rum Captain Morgan</option>
                        </select>
                        <input type="text" placeholder="Nowa nazwa" required>
                        <input type="text" placeholder="Nowa objętość" required>
                        <input type="text" placeholder="Nowa zawartość alkoholu" required>
                        <input type="text" placeholder="Nowa cena oscylacyjna" required>
                        <textarea placeholder="Nowy opis"></textarea>
                        <button type="submit">Zapisz zmiany</button>
                    </form>
                </div>
                <div class="admin-module">
                    <h2>Zarządzaj użytkownikami</h2>
                    <div class="user-management">
                        <p>Janek - <button>Usuń</button></p>
                        <p>Anna - <button>Usuń</button></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>