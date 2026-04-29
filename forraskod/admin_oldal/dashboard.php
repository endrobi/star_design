<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}

// 1. Legutóbbi 3 ÜZENET lekérése
$stmt1 = $pdo->query("SELECT * FROM uzenetek ORDER BY letrehozva DESC LIMIT 3");
$friss_uzenetek = $stmt1->fetchAll();

// 2. Legutóbbi 3 TERV lekérése
$stmt2 = $pdo->query("SELECT * FROM tervek ORDER BY letrehozva DESC LIMIT 3");
$friss_tervek = $stmt2->fetchAll();

// 3. Legutóbbi 4 GALÉRIA KÉP lekérése
$stmt3 = $pdo->query("SELECT * FROM gallery ORDER BY upload_date DESC LIMIT 4");
$friss_kepek = $stmt3->fetchAll();
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
    
</head>
<body>

<div class="header-strip">
    <div class="header-content">
        <span>Üdv, <?php echo $_SESSION["admin_nev"]; ?>!</span>
        <a href="kijelentkezes.php" class="logout-btn">Kijelentkezés</a>
    </div>
</div>

<div class="main-content">
    <h1>Irányítópult</h1>

    <div class="dashboard-grid">
        
        <!-- ÜZENETEK GYORSNÉZET -->
        <div class="stat-card">
            <h3>Legutóbbi üzenetek</h3>
            <?php foreach ($friss_uzenetek as $u): ?>
                <div class="list-item">
      <span><strong><?php echo htmlspecialchars($u['ugyfel_nev']); ?></strong>: <?php echo htmlspecialchars(substr($u['targy'], 0, 20)); ?>...</span>
                    <a href="uzenetek_reszletek.php?id=<?php echo $u['id']; ?>"class="btn-open">Megnyitás</a>
                </div>
            <?php endforeach; ?>
            <a href="levelezes.php" class="btn-all">Összes üzenet megtekintése →</a>
        
        </div>

        <!-- TERVEK GYORSNÉZET -->
        <div class="stat-card">
           <h3> Legutóbbi tervek</h3>
           <?php  
           if (!empty($friss_tervek)): 
           foreach ($friss_tervek as $t): ?>
            <div class="list-item">
                <span><strong><?php echo htmlspecialchars($t['ugyfel_nev']); ?></strong>: <?php echo htmlspecialchars($t['design_data'] ?? 'Terv'); ?></span>
                <a href="tervek_reszletek.php?id=<?php echo $t['id']; ?>" class="btn-open">Megnyitás</a>
            </div>


        <?php endforeach; 
    else: ?>
        <p style="color:#888; padding:10px;">Még nincsenek beérkezett tervek.</p>
    <?php endif; ?>
    <a href="tervek.php" class="btn-all">Összes terv megtekintése →</a>
</div>
        <!-- GALÉRIA GYORSNÉZET -->
       <div class="stat-card full-width">
    <div class="gallery-header">
        <h3>Legutóbbi galéria fotók</h3>
        <div class="btn-group">
        <a href="galeria.php" class="btn-open">Új kép hozzáadása</a>

    </div>
</div>
    <div class="gallery-row">
        <?php if (empty($friss_kepek)): ?>
            <p style="color: #888;">Még nincsenek feltöltött képek a galériában.</p>
        <?php else: ?>
            <?php foreach ($friss_kepek as $kep): ?>
                <div class="gallery-item">
                    <!-- A kép elérési útja az adatbázisból -->
                    <img src="<?php echo htmlspecialchars($kep['file_path']); ?>" 
                         alt="<?php echo htmlspecialchars($kep['title']); ?>" 
                    <p>
                        <?php echo htmlspecialchars($kep['title']); ?>
                    </p>
                      <!-- TÖRLÉS GOMB -->
                    <a href="torles_kep.php?id=<?php echo $kep['id']; ?>" 
                     class="btn-delete"
                     onclick="return confirm('Biztos törlöd?');">
                     Törlés
                   </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>


</body>
</html>
