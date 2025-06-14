<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php
require_once __DIR__ . '/../../Database.php';
$db = Database::connect();
$userLogin = $_SESSION['user']['login'] ?? null;
$user = null;
$favorites = [];
$ratings = [];
if ($userLogin) {
    $stmt = $db->prepare('SELECT * FROM users WHERE login = ?');
    $stmt->execute([$userLogin]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $db->prepare('SELECT favorite_drinks FROM users_info WHERE user_id = ?');
        $stmt->execute([$user['id']]);
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        $favIds = [];
        if ($info && $info['favorite_drinks']) {
            $favIds = array_filter(array_map('trim', explode(',', $info['favorite_drinks'])));
        }
        if ($favIds) {
            $in = implode(',', array_map('intval', $favIds));
            $drinks = $db->query("SELECT id, name FROM drinks WHERE id IN ($in)")->fetchAll(PDO::FETCH_ASSOC);

            $favMap = [];
            foreach ($drinks as $d) {
                $favMap[$d['id']] = $d['name'];
            }
            foreach ($favIds as $fid) {
                if (isset($favMap[$fid])) {
                    $favorites[] = $favMap[$fid];
                }
            }
        }

        $stmt = $db->prepare('SELECT d.name, g.rating, g.comment FROM grades g JOIN drinks d ON g.drink_id = d.id WHERE g.user_id = ?');
        $stmt->execute([$user['id']]);
        $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Użytkownika</title>
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
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="admin">Panel Admina</a>
                <?php endif; ?>
                <a href="logout">Wyloguj się</a>
            </nav>
        </header>
        <div class="user-profile">
            <div class="user-info">
                <h2>Profil: <?= htmlspecialchars($userLogin) ?></h2>
            </div>
            <div class="user-favorites">
                <h3>Ulubione trunki</h3>
                <?php if($favorites): ?>
                    <ul>
                    <?php foreach($favorites as $fav): ?>
                        <li><?= htmlspecialchars($fav) ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Brak ulubionych trunków.</p>
                <?php endif; ?>
            </div>
            <div class="user-ratings">
                <h3>Wystawione oceny</h3>
                <?php if($ratings): ?>
                    <?php foreach($ratings as $r): ?>
                        <p><?= htmlspecialchars($r['name']) ?> - <?= htmlspecialchars($r['rating']) ?>/5<?= $r['comment'] ? ' - "'.htmlspecialchars($r['comment']).'"' : '' ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak ocen.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>