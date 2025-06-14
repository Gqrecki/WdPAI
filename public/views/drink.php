<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php
require_once __DIR__ . '/../../Database.php';
$db = Database::connect();
$id = $_GET['id'] ?? null;
$drink = null;
$comments = [];
$error = null;
$userOpinion = null;
$isFavorite = false;

if ($id) {
    $stmt = $db->prepare('SELECT * FROM drinks WHERE id = ?');
    $stmt->execute([$id]);
    $drink = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT u.login, g.rating, g.comment FROM grades g JOIN users u ON g.user_id = u.id WHERE g.drink_id = ? ORDER BY g.id DESC');
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_SESSION['user'])) {
        $userLogin = $_SESSION['user']['login'];
        $stmt = $db->prepare('SELECT id FROM users WHERE login = ?');
        $stmt->execute([$userLogin]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {

            $stmt = $db->prepare('SELECT favorite_drinks FROM users_info WHERE user_id = ?');
            $stmt->execute([$user['id']]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            $favorites = [];
            if ($info && $info['favorite_drinks']) {
                $favorites = array_filter(array_map('trim', explode(',', $info['favorite_drinks'])));
            }
            $isFavorite = in_array($id, $favorites);

            $stmt = $db->prepare('SELECT * FROM grades WHERE user_id = ? AND drink_id = ?');
            $stmt->execute([$user['id'], $id]);
            $userOpinion = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']) && $id && isset($_POST['favorite_action'])) {
    $userLogin = $_SESSION['user']['login'];
    $stmt = $db->prepare('SELECT id FROM users WHERE login = ?');
    $stmt->execute([$userLogin]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $stmt = $db->prepare('SELECT * FROM users_info WHERE user_id = ?');
        $stmt->execute([$user['id']]);
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        $favorites = [];
        if ($info && $info['favorite_drinks']) {
            $favorites = array_filter(array_map('trim', explode(',', $info['favorite_drinks'])));
        }
        if ($_POST['favorite_action'] === 'add') {
            if (!in_array($id, $favorites)) {
                $favorites[] = $id;
            }
        } elseif ($_POST['favorite_action'] === 'remove') {
            $favorites = array_diff($favorites, [$id]);
        }
        $favString = implode(',', $favorites);
        if ($info) {
            $stmt = $db->prepare('UPDATE users_info SET favorite_drinks = ? WHERE user_id = ?');
            $stmt->execute([$favString, $user['id']]);
        } else {
            $stmt = $db->prepare('INSERT INTO users_info (user_id, favorite_drinks) VALUES (?, ?)');
            $stmt->execute([$user['id'], $favString]);
        }
        header('Location: drink?id=' . urlencode($id));
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user']) && $id && !isset($_POST['favorite_action'])) {
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $userLogin = $_SESSION['user']['login'];
    $stmt = $db->prepare('SELECT id FROM users WHERE login = ?');
    $stmt->execute([$userLogin]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $rating >= 1 && $rating <= 5) {
 
        $stmt = $db->prepare('SELECT * FROM grades WHERE user_id = ? AND drink_id = ?');
        $stmt->execute([$user['id'], $id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {

            $stmt = $db->prepare('UPDATE grades SET rating = ?, comment = ? WHERE id = ?');
            $stmt->execute([$rating, $comment, $existing['id']]);
        } else {

            $stmt = $db->prepare('INSERT INTO grades (user_id, drink_id, rating, comment) VALUES (?, ?, ?, ?)');
            $stmt->execute([$user['id'], $id, $rating, $comment]);
        }
        header('Location: drink?id=' . urlencode($id));
        exit();
    } else {
        $error = "Nieprawidłowa ocena lub błąd użytkownika.";
    }

    if ($user) {
        $stmt = $db->prepare('SELECT * FROM grades WHERE user_id = ? AND drink_id = ?');
        $stmt->execute([$user['id'], $id]);
        $userOpinion = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

function add_unit($val, $unit) {
    if(!$val) return '';
    $val = trim($val);
    if($unit === 'L' && !preg_match('/l$/i', $val)) return $val.'L';
    if($unit === '%' && !preg_match('/%$/', $val)) return $val.'%';
    if($unit === 'zł' && !preg_match('/zł$/i', $val)) return $val.' zł';
    return $val;
}
?>
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
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="admin">Panel Admina</a>
                <?php endif; ?>
                <a href="logout">Wyloguj się</a>
            </nav>
        </header>
        <div class="drink-details">
            <?php if($drink): ?>
            <div class="drink-info">
                <h2><?= htmlspecialchars($drink['name']) ?></h2>
                <p>Objętość: <?= htmlspecialchars(add_unit($drink['volume'], 'L')) ?></p>
                <p>Zawartość alkoholu: <?= htmlspecialchars(add_unit($drink['alcohol_content'], '%')) ?></p>
                <p>Cena oscylacyjna: <?= htmlspecialchars(add_unit($drink['price_range'], 'zł')) ?></p>
                <p><?= nl2br(htmlspecialchars($drink['description'])) ?></p>
                <?php if(isset($_SESSION['user'])): ?>
                    <form method="POST" action="drink?id=<?= urlencode($id) ?>" style="display:inline;">
                        <?php if($isFavorite): ?>
                            <button type="submit" name="favorite_action" value="remove">Usuń z ulubionych</button>
                        <?php else: ?>
                            <button type="submit" name="favorite_action" value="add">Dodaj do ulubionych</button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="drink-info">
                <p>Nie znaleziono trunku.</p>
            </div>
            <?php endif; ?>
            <div class="drink-rating">
                <h3>Twoja ocena</h3>
                <?php if(isset($_SESSION['user'])): ?>
                    <?php if($userOpinion): ?>
                        <form method="POST" action="drink?id=<?= urlencode($id) ?>">
                            <select name="rating" required>
                                <option value="">Wybierz ocenę</option>
                                <?php for($i=1;$i<=5;$i++): ?>
                                    <option value="<?= $i ?>" <?= $userOpinion['rating'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <textarea name="comment" placeholder="Twój komentarz"><?= htmlspecialchars($userOpinion['comment']) ?></textarea>
                            <button type="submit">Zmień opinię</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="drink?id=<?= urlencode($id) ?>">
                            <select name="rating" required>
                                <option value="">Wybierz ocenę</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <textarea name="comment" placeholder="Twój komentarz"></textarea>
                            <button type="submit">Wyślij</button>
                        </form>
                    <?php endif; ?>
                    <?php if($error): ?>
                        <p style="color:red"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Aby ocenić i skomentować, <a href="login">zaloguj się</a>.</p>
                <?php endif; ?>
            </div>
            <div class="drink-comments">
                <h3>Komentarze</h3>
                <?php if($comments): ?>
                    <?php foreach($comments as $c): ?>
                        <p>
                            <strong><?= htmlspecialchars($c['login']) ?>:</strong>
                            (Ocena: <?= htmlspecialchars($c['rating']) ?>/5)
                            <?= htmlspecialchars($c['comment']) ?>
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak komentarzy.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>