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
$versione = getenv('APP_VERSION') ?: 'dev';
?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Note</title>
<style>
  :root {
    --bg: #f4f1ea; --card: #fffdf8; --text: #2b2a27; --muted: #8a857a;
    --accent: #2f6f5e; --accent-hover: #255a4c; --border: #e4ded1;
  }
  @media (prefers-color-scheme: dark) {
    :root {
      --bg: #1c1d1b; --card: #262724; --text: #ece8de; --muted: #9b968a;
      --accent: #5fb39a; --accent-hover: #74c4ab; --border: #383933;
    }
  }
  * { box-sizing: border-box; }
  body {
    margin: 0; min-height: 100vh; background: var(--bg); color: var(--text);
    font-family: "Segoe UI", system-ui, sans-serif;
    display: flex; justify-content: center; padding: 48px 16px;
  }
  .app { width: 100%; max-width: 560px; }
  header { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 24px; }
  h1 { font-size: 2rem; margin: 0; letter-spacing: -0.02em; }
  .badge {
    font-size: .75rem; color: var(--muted); border: 1px solid var(--border);
    padding: 4px 10px; border-radius: 999px;
  }
  form { display: flex; gap: 8px; margin-bottom: 28px; }
  input {
    flex: 1; padding: 12px 14px; font-size: 1rem; color: var(--text);
    background: var(--card); border: 1px solid var(--border); border-radius: 10px;
  }
  input:focus { outline: 2px solid var(--accent); border-color: transparent; }
  button {
    padding: 12px 18px; font-size: 1rem; font-weight: 600; color: #fff;
    background: var(--accent); border: 0; border-radius: 10px; cursor: pointer;
  }
  button:hover { background: var(--accent-hover); }
  .count { color: var(--muted); font-size: .85rem; margin: 0 0 10px; }
  ul { list-style: none; padding: 0; margin: 0; display: grid; gap: 10px; }
  li {
    background: var(--card); border: 1px solid var(--border); border-radius: 12px;
    padding: 14px 16px; display: flex; justify-content: space-between; gap: 12px;
  }
  .data { color: var(--muted); font-size: .8rem; white-space: nowrap; }
  .vuoto { text-align: center; color: var(--muted); padding: 40px 0; }
</style>
</head>
<body>
<main class="app">
  <header>
    <h1>Le mie note</h1>
    <span class="badge">v<?= htmlspecialchars($versione) ?></span>
  </header>

  <form method="post">
    <input name="testo" placeholder="Scrivi una nota…" required autofocus>
    <button>Aggiungi</button>
  </form>

  <?php if (count($note) === 0): ?>
    <p class="vuoto">Nessuna nota. Scrivi la prima!</p>
  <?php else: ?>
    <p class="count"><?= count($note) ?> note salvate</p>
    <ul>
    <?php foreach ($note as $n): ?>
      <li>
        <span><?= htmlspecialchars($n['testo']) ?></span>
        <span class="data"><?= date('d.m.Y H:i', strtotime($n['creato'])) ?></span>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</main>
</body>
</html>