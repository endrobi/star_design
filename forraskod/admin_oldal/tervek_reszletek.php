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
    $stmt = $pdo->prepare("SELECT * FROM tervek WHERE id = ?");
    $stmt->execute([$id]);
    $msg = $stmt->fetch();
    if (!$msg) { die("Hiba: A tervek nem található!"); }
}

// 2. FELDOLGOZVA ÁLLAPOT MENTÉSE (Ha rákattintasz a gombra)
if (isset($_POST['mentes'])) {
    $id = $_GET['id'];
    $megjegyzes = $_POST['megjegyzes'];

    try {
        if (!empty($megjegyzes)) {
            // Itt a 'feldolgozva' szót tesszük az állapotba
            $sql = "UPDATE tervek SET admin_megjegyzes = ?, feldolgozva_ekkor = NOW(), allapot = 'feldolgozva' WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$megjegyzes, $id]);
        } else {
            // Ha üres a megjegyzés, visszaállítjuk 'uj' állapotba
            $sql = "UPDATE tervek SET admin_megjegyzes = NULL, feldolgozva_ekkor = NULL, allapot = 'uj' WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
        }
        header("Location: tervek_reszletek.php?id=" . $id . "&siker=1");
        exit();
    } catch (PDOException $e) {
        // Ha hiba van, írja ki pontosan mi az!
        die("Adatbázis hiba a mentésnél: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Üzenet megnyitása</title>
    <!-- Fontos: ellenőrizd, hogy az elérési út jó-e! -->
    <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
</head>
<body>
    
    <div class="main">
        <a href="tervek.php" class="btn">← Vissza</a>

        <p><strong>Ügyfél neve:</strong> <?php echo htmlspecialchars($msg['ugyfel_nev']); ?> (<?php echo htmlspecialchars($msg['ugyfel_email']); ?>)</p>
        <p><strong>Dátum:</strong> <?php echo $msg['letrehozva']; ?></p>

        <div class="message-box">
            <?php echo nl2br(htmlspecialchars($msg['design_data'])); ?>
        </div>

        <div class="admin-box">
            <h3>Feldolgozási adatok</h3>
            <form method="POST">
                <div style="margin-bottom: 10px;">
                    <label>Admin megjegyzés (pl. Válaszolva):</label><br>
                    <input type="text" name="megjegyzes" class="admin-input"
                           value="<?php echo htmlspecialchars($msg['admin_megjegyzes'] ?? ''); ?>" 
                           placeholder="Írd ide: Válaszolva">
                </div>

                <button type="submit" name="mentes" class="btn-save">
                    Állapot mentése
                </button>
                
                <a href="torles.php?id=<?php echo $msg['id']; ?>&tipus=tervek" 
                   onclick="return confirm('Biztosan törölni akarod ezt a tervet?')" 
                   class="btn-delete">
                   🗑️ Törlés
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

