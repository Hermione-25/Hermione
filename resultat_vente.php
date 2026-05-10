<?php
// ── Vérification : données reçues via POST ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: effectuer.html');
    exit;
}


// Construction du panier (uniquement les articles avec qté > 0)
$panier = [];
foreach ($qtes as $idarticle => $qte) {
    $q = intval($qte);
    if ($q > 0 && isset($articles[$idarticle])) {
        $prix   = floatval($articles[$idarticle]['prix']);
        $panier[] = [
            'idarticle' => htmlspecialchars($idarticle),
            'design'    => htmlspecialchars($articles[$idarticle]['design']),
            'categorie' => htmlspecialchars($articles[$idarticle]['cat']),
            'prix'      => $prix,
            'qte'       => $q,
            'montant'   => $prix * $q,
        ];
    }
}

// Si aucun article sélectionné
if (empty($panier)) {
    header('Location: effectuer.html');
    exit;
}

// Total général
$grandTotal = array_sum(array_column($panier, 'montant'));

// Numéro de vente fictif (à remplacer par un ID réel de BD)
$numVente = 'VTE-' . strtoupper(substr(md5(time()), 0, 6));
$dateVente = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Récapitulatif de vente</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --bg:      #f5f0e8;
    --surface: #ffffff;
    --primary: #1a3c2b;
    --accent:  #e07b39;
    --success: #2d6a4f;
    --muted:   #8a8070;
    --border:  #ddd5c8;
    --danger:  #c0392b;
    --radius:  12px;
    --shadow:  0 2px 12px rgba(0,0,0,0.08);
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: #2c2416;
    min-height: 100vh;
  }

  /* ── HEADER ── */
  header {
    background: var(--primary);
    color: #fff;
    padding: 18px 40px;
    display: flex;
    align-items: center;
    gap: 14px;
  }
  header h1 {
    font-family: 'DM Serif Display', serif;
    font-size: 1.5rem;
  }
  header .meta {
    margin-left: auto;
    text-align: right;
    font-size: 0.82rem;
    opacity: 0.7;
    line-height: 1.6;
  }

  main { max-width: 960px; margin: 0 auto; padding: 40px 24px 60px; }

  /* ── BANNER SUCCÈS ── */
  .success-banner {
    background: linear-gradient(135deg, var(--success) 0%, #1a4a36 100%);
    color: #fff;
    border-radius: var(--radius);
    padding: 24px 32px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 4px 20px rgba(45,106,79,0.35);
    animation: slideIn .4s ease;
  }
  @keyframes slideIn {
    from { opacity:0; transform:translateY(-12px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .success-icon { font-size: 2.5rem; }
  .success-text h2 {
    font-family: 'DM Serif Display', serif;
    font-size: 1.3rem;
    margin-bottom: 4px;
  }
  .success-text p { font-size: 0.88rem; opacity: 0.85; }
  .success-num {
    margin-left: auto;
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
    padding: 10px 18px;
    text-align: center;
  }
  .success-num span { display:block; font-size:0.7rem; opacity:.7; margin-bottom:2px; }
  .success-num strong { font-size: 1.1rem; letter-spacing: 0.06em; }

  /* ── SECTION TITLE ── */
  .section-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.15rem;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
  }
  .section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(to right, var(--border), transparent);
  }

  /* ── FICHE CLIENT ── */
  .client-fiche {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 22px 28px;
    margin-bottom: 32px;
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
  }
  .fiche-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 100px;
  }
  .fiche-item label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
  }
  .fiche-item strong {
    font-size: 0.98rem;
    font-weight: 600;
    color: #2c2416;
  }

  /* ── TABLEAU ── */
  .table-wrap {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 28px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  thead tr {
    background: var(--primary);
    color: #fff;
  }
  thead th {
    padding: 14px 16px;
    text-align: left;
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    white-space: nowrap;
  }
  thead th.right { text-align: right; }

  tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .15s;
    animation: fadeRow .35s ease both;
  }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: #faf6ef; }

  @keyframes fadeRow {
    from { opacity:0; transform:translateX(-8px); }
    to   { opacity:1; transform:translateX(0); }
  }
  <?php foreach ($panier as $i => $_): ?>
  tbody tr:nth-child(<?= $i+1 ?>) { animation-delay: <?= $i * 0.06 ?>s; }
  <?php endforeach; ?>

  tbody td {
    padding: 13px 16px;
    font-size: 0.92rem;
    vertical-align: middle;
  }
  .td-right { text-align: right; }
  .td-center { text-align: center; }

  .badge-id {
    background: #eef3f0;
    color: var(--primary);
    border: 1px solid #c8ddd2;
    border-radius: 5px;
    padding: 2px 8px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    font-family: monospace;
  }
  .nom-cell strong { display: block; font-weight: 600; }
  .nom-cell span   { font-size: 0.8rem; color: var(--muted); }
  .prix-cell  { color: var(--muted); }
  .qte-cell   { font-weight: 700; color: var(--primary); }
  .mont-cell  { font-weight: 700; color: var(--accent); }

  /* ── TFOOT TOTAL ── */
  tfoot tr {
    background: #f0ece4;
  }
  tfoot td {
    padding: 16px;
    font-weight: 700;
    font-size: 1rem;
  }
  .total-label { color: var(--primary); letter-spacing: 0.04em; }
  .total-value {
    text-align: right;
    font-family: 'DM Serif Display', serif;
    font-size: 1.35rem;
    color: var(--accent);
  }

  /* ── RÉSUMÉ STATS ── */
  .stats-row {
    display: flex;
    gap: 16px;
    margin-bottom: 32px;
    flex-wrap: wrap;
  }
  .stat-card {
    flex: 1;
    min-width: 140px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px 22px;
    text-align: center;
    box-shadow: var(--shadow);
  }
  .stat-card .val {
    font-family: 'DM Serif Display', serif;
    font-size: 1.6rem;
    color: var(--primary);
    display: block;
    margin-bottom: 4px;
  }
  .stat-card .lbl { font-size: 0.75rem; color: var(--muted); font-weight: 500; }

  /* ── ACTIONS ── */
  .actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    padding-top: 8px;
  }
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 32px;
    border: none;
    border-radius: 50px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
  }
  .btn-primary {
    background: var(--primary);
    color: #fff;
    box-shadow: 0 4px 16px rgba(26,60,43,0.3);
  }
  .btn-primary:hover {
    background: #14301f;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26,60,43,0.35);
  }
  .btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
  }
  .btn-outline:hover {
    background: var(--primary);
    color: #fff;
    transform: translateY(-2px);
  }
  .btn-print {
    background: transparent;
    color: var(--muted);
    border: 2px solid var(--border);
  }
  .btn-print:hover {
    background: var(--border);
    color: #2c2416;
  }

  @media print {
    header, .success-banner, .actions, .btn, .stats-row { display: none !important; }
    body { background: #fff; }
    main { padding: 0; max-width: 100%; }
    .table-wrap { box-shadow: none; border: 1px solid #ccc; }
  }

  @media(max-width:640px) {
    header { flex-wrap: wrap; }
    .fiche-item { min-width: 45%; }
    .stats-row .stat-card { min-width: 40%; }
  }
</style>
</head>
<body>

<header>
  <span style="font-size:1.8rem">🧾</span>
  <h1>Récapitulatif de vente</h1>
  <div class="meta">
    <div><?= $dateVente ?></div>
    <div><?= $numVente ?></div>
  </div>
</header>

<main>

  <!-- Bannière succès -->
  <div class="success-banner">
    <div class="success-icon">✅</div>
    <div class="success-text">
      <h2>Vente enregistrée avec succès</h2>
      <p>Le récapitulatif complet de la transaction est présenté ci-dessous.</p>
    </div>
    <div class="success-num">
      <span>Référence</span>
      <strong><?= $numVente ?></strong>
    </div>
  </div>

  <!-- Fiche client -->
  <p class="section-title">👤 Client</p>
  <div class="client-fiche">
    <div class="fiche-item">
      <label>Nom</label>
      <strong><?= $nom ?></strong>
    </div>
    <div class="fiche-item">
      <label>Prénom</label>
      <strong><?= $prenom ?></strong>
    </div>
    <div class="fiche-item">
      <label>Âge</label>
      <strong><?= $age ?> ans</strong>
    </div>
    <div class="fiche-item">
      <label>Adresse</label>
      <strong><?= $adresse ?: '—' ?></strong>
    </div>
    <div class="fiche-item">
      <label>Ville</label>
      <strong><?= $ville ?: '—' ?></strong>
    </div>
  </div>

  <!-- Stats rapides -->
  <div class="stats-row">
    <div class="stat-card">
      <span class="val"><?= count($panier) ?></span>
      <span class="lbl">Article(s) acheté(s)</span>
    </div>
    <div class="stat-card">
      <span class="val"><?= array_sum(array_column($panier, 'qte')) ?></span>
      <span class="lbl">Unité(s) au total</span>
    </div>
    <div class="stat-card">
      <span class="val"><?= number_format($grandTotal, 0, '.', ' ') ?></span>
      <span class="lbl">FCFA — Montant total</span>
    </div>
  </div>

  <!-- Tableau des articles -->
  <p class="section-title">📦 Détail des articles</p>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nom &amp; Prénom</th>
          <th>ID Article</th>
          <th>Désignation</th>
          <th>Catégorie</th>
          <th class="right">Prix unit.</th>
          <th class="right">Qté</th>
          <th class="right">Montant</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($panier as $i => $ligne): ?>
        <tr>
          <td class="td-center" style="color:var(--muted);font-size:.85rem;"><?= $i + 1 ?></td>
          <td class="nom-cell">
            <strong><?= $nom ?> <?= $prenom ?></strong>
          </td>
          <td><span class="badge-id"><?= $ligne['idarticle'] ?></span></td>
          <td><?= $ligne['design'] ?></td>
          <td style="font-size:.82rem;color:var(--muted);"><?= $ligne['categorie'] ?></td>
          <td class="td-right prix-cell"><?= number_format($ligne['prix'], 0, '.', ' ') ?> F</td>
          <td class="td-right qte-cell">× <?= $ligne['qte'] ?></td>
          <td class="td-right mont-cell"><?= number_format($ligne['montant'], 0, '.', ' ') ?> F</td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7" class="total-label">MONTANT TOTAL DE LA VENTE</td>
          <td class="total-value"><?= number_format($grandTotal, 0, '.', ' ') ?> FCFA</td>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- Boutons d'action -->
  <div class="actions">
    <a href="effectuer_vente.php" class="btn btn-outline">⬅ Nouvelle vente</a>
    <button onclick="window.print()" class="btn btn-print">🖨 Imprimer</button>
  </div>

</main>

</body>
</html>
