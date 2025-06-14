<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php
require_once __DIR__ . '/../../Database.php';
$db = Database::connect();
$search = $_GET['q'] ?? '';
$users = [];
if ($search) {
    $stmt = $db->prepare('SELECT * FROM users WHERE login ILIKE ?');
    $stmt->execute(['%'.$search.'%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyszukiwanie Użytkowników</title>
    <link rel="stylesheet" href="public/styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
        <div class="search-panel">
            <form method="GET" action="search">
                <input type="text" name="q" placeholder="Wyszukaj użytkownika..." value="<?= htmlspecialchars($search) ?>" required>
                <button type="submit">Szukaj</button>
            </form>
            <h2>Wyniki wyszukiwania</h2>
            <div class="user-list">
                <?php if($search === ''): ?>
                    <p>Wpisz nazwę użytkownika, aby rozpocząć wyszukiwanie.</p>
                <?php elseif($users): ?>
                    <?php foreach($users as $user): ?>
                        <div class="user-card">
                            <h3><?= htmlspecialchars($user['login']) ?></h3>
                            <?php
                            $stmt = $db->prepare('SELECT favorite_drinks FROM users_info WHERE user_id = ?');
                            $stmt->execute([$user['id']]);
                            $info = $stmt->fetch(PDO::FETCH_ASSOC);
                            $favNames = [];
                            if ($info && $info['favorite_drinks']) {
                                $favIds = array_filter(array_map('trim', explode(',', $info['favorite_drinks'])));
                                if ($favIds) {
                                    $in = implode(',', array_map('intval', $favIds));
                                    $drinks = $db->query("SELECT id, name FROM drinks WHERE id IN ($in)")->fetchAll(PDO::FETCH_ASSOC);
                                    $favMap = [];
                                    foreach ($drinks as $d) {
                                        $favMap[$d['id']] = $d['name'];
                                    }
                                    foreach ($favIds as $fid) {
                                        if (isset($favMap[$fid])) {
                                            $favNames[] = $favMap[$fid];
                                        }
                                    }
                                }
                            }
                            ?>
                            <p><strong>Ulubione trunki:</strong></p>
                            <?php
                            $favCount = count($favNames);
                            for($i = 0; $i < min(3, $favCount); $i++): ?>
                                <p><?= htmlspecialchars($favNames[$i]) ?></p>
                            <?php endfor; ?>
                            <?php if($favCount > 3): ?>
                                <p>...</p>
                            <?php elseif($favCount === 0): ?>
                                <p>Brak</p>
                            <?php endif; ?>

                            <p><strong>Oceny:</strong></p>
                            <?php
                            $stmt = $db->prepare('SELECT d.name, g.rating FROM grades g JOIN drinks d ON g.drink_id = d.id WHERE g.user_id = ?');
                            $stmt->execute([$user['id']]);
                            $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $ratCount = count($ratings);
                            for($i = 0; $i < min(3, $ratCount); $i++): ?>
                                <p><?= htmlspecialchars($ratings[$i]['name']) ?> - <?= htmlspecialchars($ratings[$i]['rating']) ?>/5</p>
                            <?php endfor; ?>
                            <?php if($ratCount > 3): ?>
                                <p>...</p>
                            <?php elseif($ratCount === 0): ?>
                                <p>Brak ocen.</p>
                            <?php endif; ?>

                            <a href="user?login=<?= urlencode($user['login']) ?>">Zobacz profil</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak wyników.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>