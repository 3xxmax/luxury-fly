<?php
$pdo = new PDO('mysql:host=localhost;dbname=nos_voyage;charset=utf8', 'root', '');

// Liste des 15 destinations
$destinations = $pdo->query("SELECT * FROM Destination LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);

// Filtres
$paysFilter = $_GET['pays'] ?? '';
$prixFilter = $_GET['prix_max'] ?? '';
$dateFilter = $_GET['date_depart'] ?? '';

// Préparation des données voyages pour chaque destination
$voyages = [];
foreach ($destinations as $dest) {
    $sql = "SELECT * FROM Voyage WHERE id_destination = :id";

    $conditions = [];
    $params = [':id' => $dest['id_destination']];

    if (!empty($prixFilter)) {
        $conditions[] = "prix <= :prix";
        $params[':prix'] = $prixFilter;
    }
    if (!empty($dateFilter)) {
        $conditions[] = "date_depart >= :date";
        $params[':date'] = $dateFilter;
    }

    if ($conditions) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $voyages[$dest['id_destination']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <meta charset="UTF-8" />
  <title>SKYN - Destinations</title>
  <link rel="stylesheet" href="test.css" />
</head>
<body>

<header>
  <h1>SKYN - Explorez nos 15 destinations</h1>
</header>

<div class="search-container">
  <form method="GET">
    <select name="pays">
      <option value="">-- Pays --</option>
      <?php
      $paysList = $pdo->query("SELECT DISTINCT pays FROM Destination LIMIT 15")->fetchAll(PDO::FETCH_COLUMN);
      foreach ($paysList as $p):
      ?>
        <option value="<?= htmlspecialchars($p) ?>" <?= $p === $paysFilter ? 'selected' : '' ?>>
          <?= htmlspecialchars($p) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="number" name="prix_max" placeholder="Prix max (€)" value="<?= htmlspecialchars($prixFilter) ?>" />
    <input type="date" name="date_depart" value="<?= htmlspecialchars($dateFilter) ?>" />
    <button type="submit">Filtrer</button>
  </form>
</div>

<section class="destinations">
  <?php foreach ($destinations as $dest): ?>
    <?php if (empty($paysFilter) || $dest['pays'] === $paysFilter): ?>
      <div class="card">
        <img src="<?= strtolower($dest['nom']) ?>.jpg" alt="<?= htmlspecialchars($dest['nom']) ?>">
        <div class="overlay">
          <h2><?= htmlspecialchars($dest['nom']) ?> (<?= htmlspecialchars($dest['pays']) ?>)</h2>
          <p><?= htmlspecialchars($dest['description']) ?></p>
          <?php if (!empty($voyages[$dest['id_destination']])): ?>
            <?php foreach ($voyages[$dest['id_destination']] as $v): ?>
              <p>Départ : <?= $v['date_depart'] ?> | Retour : <?= $v['date_retour'] ?></p>
              <p>À partir de <?= number_format($v['prix'], 2, ',', ' ') ?>€</p>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="no-voyage">Aucun voyage disponible selon les critères</p>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</section>

</body>
</html>y