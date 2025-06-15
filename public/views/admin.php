<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login');
    exit();
}
require_once __DIR__ . '/../../Database.php';
$db = Database::connect();

$drinks = $db->query('SELECT * FROM drinks')->fetchAll(PDO::FETCH_ASSOC);
$users = $db->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=export.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Trunki']);
    fputcsv($output, ['id', 'name', 'volume', 'alcohol_content', 'price_range', 'description']);
    foreach($drinks as $drink) {
        fputcsv($output, [
            $drink['id'],
            $drink['name'],
            $drink['volume'],
            $drink['alcohol_content'],
            $drink['price_range'],
            $drink['description']
        ]);
    }
    fputcsv($output, []);
    fputcsv($output, ['Użytkownicy']);
    fputcsv($output, ['id', 'login', 'role']);
    foreach($users as $user) {
        fputcsv($output, [
            $user['id'],
            $user['login'],
            $user['role']
        ]);
    }
    fclose($output);
    exit();
}
?>
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
                <a href="logout">Wyloguj się</a>
            </nav>
        </header>
        <div class="admin-panel">
            <div style="margin-bottom:20px;">
                <form method="get" action="admin" style="display:inline;">
                    <button type="submit" name="export" value="csv">Eksportuj dane do CSV</button>
                </form>
            </div>
            <div class="admin-grid">
                <div class="admin-module">
                    <h2>Dodaj trunek</h2>
                    <form action="admin" method="POST">
                        <input type="text" name="name" placeholder="Nazwa trunku" required>
                        <input type="text" name="volume" placeholder="Objętość [L]" required>
                        <input type="text" name="alcohol_content" placeholder="Zawartość alkoholu [%]" required>
                        <input type="text" name="price_range" placeholder="Cena oscylacyjna [zł]" required>
                        <textarea name="description" placeholder="Opis trunku"></textarea>
                        <button type="submit" name="add_drink">Dodaj</button>
                    </form>
                </div>
                <div class="admin-module">
                    <h2>Trunki</h2>
                    <?php foreach($drinks as $drink): ?>
                        <div>
                            <strong><?= htmlspecialchars($drink['name']) ?></strong>
                            (<?= htmlspecialchars($drink['volume']) ?>, <?= htmlspecialchars($drink['alcohol_content']) ?>, <?= htmlspecialchars($drink['price_range']) ?>)
                            <form action="admin" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_drink_id" value="<?= $drink['id'] ?>">
                                <button type="submit">Usuń</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="admin-module">
                    <h2>Użytkownicy</h2>
                    <?php foreach($users as $user): ?>
                        <div>
                            <?= htmlspecialchars($user['login']) ?> (<?= htmlspecialchars($user['role']) ?>)
                            <?php if($user['role'] !== 'admin'): ?>
                            <form action="admin" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                <button type="submit">Usuń</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>