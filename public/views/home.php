<?php if(session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona Główna - DrinkAdvisor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/styles/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sortSelect = document.getElementById('sort');
            if(sortSelect) {
                sortSelect.addEventListener('change', function() {
                    window.location.href = 'home?sort=' + encodeURIComponent(this.value);
                });
            }
        });
    </script>
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
        <div class="sort-section">
            <label for="sort">Sortuj według:</label>
            <select id="sort" name="sort">
                <?php
                $sort = $_GET['sort'] ?? 'trending';
                $options = [
                    'trending' => 'Trendujące',
                    'best' => 'Najlepsze',
                    'worst' => 'Najgorsze',
                    'expensive' => 'Najdroższe',
                    'cheap' => 'Najtańsze'
                ];
                foreach($options as $val => $label) {
                    echo '<option value="'.$val.'"'.($sort === $val ? ' selected' : '').'>'.$label.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="grid">
            <?php
            require_once __DIR__ . '/../../Database.php';
            $db = Database::connect();

            $drinks = $db->query('SELECT * FROM drinks')->fetchAll(PDO::FETCH_ASSOC);

            $avgRatings = [];
            $stmt = $db->query('SELECT drink_id, AVG(rating) as avg_rating FROM grades GROUP BY drink_id');
            foreach($stmt as $row) {
                $avgRatings[$row['drink_id']] = round($row['avg_rating'], 1); // zmiana: jedno miejsce po przecinku
            }

            foreach($drinks as &$drink) {
                $drink['avg_rating'] = $avgRatings[$drink['id']] ?? null;
            }
            unset($drink);

            function extract_price($priceRange, $type = 'min') {
                if(preg_match_all('/\d+/', $priceRange, $matches)) {
                    $nums = array_map('intval', $matches[0]);
                    if(!$nums) return null;
                    return $type === 'max' ? max($nums) : min($nums);
                }
                return null;
            }

            if ($sort === 'trending') {
                usort($drinks, function($a, $b) {
                    return intval($b['id']) <=> intval($a['id']); // Najnowsze (największe id) wyżej
                });
            } elseif ($sort === 'best') {
                usort($drinks, function($a, $b) {
                    return ($b['avg_rating'] ?? 0) <=> ($a['avg_rating'] ?? 0);
                });
            } elseif ($sort === 'worst') {
                usort($drinks, function($a, $b) {
                    return ($a['avg_rating'] ?? 999) <=> ($b['avg_rating'] ?? 999);
                });
            } elseif ($sort === 'expensive') {
                usort($drinks, function($a, $b) {
                    return (extract_price($b['price_range'], 'max') ?? 0) <=> (extract_price($a['price_range'], 'max') ?? 0);
                });
            } elseif ($sort === 'cheap') {
                usort($drinks, function($a, $b) {
                    return (extract_price($a['price_range'], 'min') ?? 99999) <=> (extract_price($b['price_range'], 'min') ?? 99999);
                });
            }

            function add_unit($val, $unit) {
                if(!$val) return '';
                $val = trim($val);
                if($unit === 'L' && !preg_match('/l$/i', $val)) return $val.'L';
                if($unit === '%' && !preg_match('/%$/', $val)) return $val.'%';
                if($unit === 'zł' && !preg_match('/zł$/i', $val)) return $val.' zł';
                return $val;
            }
            foreach($drinks as $drink): ?>
                <div class="drink-card">
                    <h2><?= htmlspecialchars($drink['name']) ?></h2>
                    <p>Objętość: <?= htmlspecialchars(add_unit($drink['volume'], 'L')) ?></p>
                    <p>Zawartość alkoholu: <?= htmlspecialchars(add_unit($drink['alcohol_content'], '%')) ?></p>
                    <p>Cena: <?= htmlspecialchars(add_unit($drink['price_range'], 'zł')) ?></p>
                    <p>
                        Średnia ocena:
                        <?php if($drink['avg_rating'] !== null): ?>
                            <strong><?= number_format($drink['avg_rating'], 1, '.', '') ?>/5</strong>
                        <?php else: ?>
                            <span>Brak ocen</span>
                        <?php endif; ?>
                    </p>
                    <a href="drink?id=<?= $drink['id'] ?>">Szczegóły</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>