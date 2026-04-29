<?php
session_start();
require_once "db_connect.php"; // PDO kapcsolat ($pdo)

if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}

// 1. ADATOK LEKÉRÉSE
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM uzenetek WHERE id = ?");
    $stmt->execute([$id]);
    $msg = $stmt->fetch();
    if (!$msg) { die("Hiba: Az üzenet nem található!"); }
}

// 2. FELDOLGOZVA ÁLLAPOT MENTÉSE (Ha rákattintasz a gombra)
if (isset($_POST['mentes'])) {
    $megjegyzes = $_POST['megjegyzes'];
    $id = $_GET['id'];

    // Ha írtál be valamit, elmentjük azt és a jelenlegi időt
    if (!empty($megjegyzes)) {
        $stmt = $pdo->prepare("UPDATE uzenetek SET admin_megjegyzes = ?, feldolgozva_ekkor = NOW() WHERE id = ?");
        $stmt->execute([$megjegyzes, $id]);
    } else {
        // Ha üresen hagyod, töröljük a státuszt
        $stmt = $pdo->prepare("UPDATE uzenetek SET admin_megjegyzes = NULL, feldolgozva_ekkor = NULL WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: uzenetek_reszletek.php?id=" . $id);
    exit();

}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Üzenet megnyitása</title>
    <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
</head>
<body>
    <div class="main" >
        <a href="uzenetek.php" class="btn">← Vissza</a>
        <hr>
        <h2>Tárgy: <?php echo htmlspecialchars($msg['targy']); ?></h2>
        <p><strong>Feladó:</strong> <?php echo htmlspecialchars($msg['ugyfel_nev']); ?> (<?php echo htmlspecialchars($msg['ugyfel_email']); ?>)</p>
        <p><strong>Dátum:</strong> <?php echo $msg['letrehozva']; ?></p>

        <div class="message-box">
            <?php echo nl2br(htmlspecialchars($msg['uzenet'])); ?>
        </div>

    <div class="admin-box">  
    <h3>Feldolgozási adatok</h3>
    <form method="POST">
        <div style="margin-bottom: 10px;">
            <label>Admin megjegyzés (pl. Válaszolva):</label><br>
            <input type="text" name="megjegyzes" 
                   value="<?php echo htmlspecialchars($msg['admin_megjegyzes'] ?? ''); ?>" 
                   placeholder="Írd ide: Válaszolva"
                   style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc;">
        </div>

        <button type="submit" name="mentes" class="btn-save"> Állapot mentése
        </button>
        <a href="torles.php?id=<?php echo $msg['id']; ?>&tipus=uzenetek" 
           onclick="return confirm('Biztosan törölni akarod ezt az üzenetet?')" 
          class="btn-delete">🗑️ Törlés
        </a>
</form>

    <?php if($msg['feldolgozva_ekkor']): ?>
          <p class="status-text">
            <strong>Mentve:</strong> <?php echo $msg['feldolgozva_ekkor']; ?>
        </p>
    <?php endif; ?>
</div>

    </div>
</body>
</html>
