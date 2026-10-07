<?php
$pdo = new PDO(
    "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4",
    getenv('DB_USER'), getenv('DB_PASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
$pdo->exec("CREATE TABLE IF NOT EXISTS note (
    id INT AUTO_INCREMENT PRIMARY KEY,
    testo VARCHAR(255) NOT NULL,
    creato TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['testo'])) {
    $pdo->prepare("INSERT INTO note (testo) VALUES (?)")->execute([$_POST['testo']]);
    header('Location: /');
    exit;
}
$note = $pdo->query("SELECT * FROM note ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html><body>
<h1>Le mie note</h1>
<form method="post">
  <input name="testo" required> <button>Aggiungi</button>
</form>
<ul>
<?php foreach ($note as $n): ?>
  <li><?= htmlspecialchars($n['testo']) ?> (<?= $n['creato'] ?>)</li>
<?php endforeach; ?>
</ul>
<p>Versione: <?= htmlspecialchars(getenv('APP_VERSION') ?: 'dev') ?></p>
</body></html>