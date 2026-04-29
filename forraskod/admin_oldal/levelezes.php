<?php
session_start();
require_once "db_connect.php"; // Kapcsolódás az adatbázishoz

// 1. BIZTONSÁGI ELLENŐRZÉS: Csak belépett admin láthatja!
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}

// 2. ADATOK LEKÉRÉSE: Az összes üzenet lekérése, a legfrissebb legyen legfelül
$stmt = $pdo->query("SELECT * FROM uzenetek ORDER BY letrehozva DESC");
$uzenetek = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Üzenetek kezelése</title>
    <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
   
</head>
<body>

    <div class="header-strip">
      <div class="header-content">
       
           <a href="dashboard.php" class="btn">← Vissza az irányítópulthoz</a>
           <a href="kijelentkezes.php" class="logout-btn">Kijelentkezés</a> 
      </div>    
    </div>

   <div class="main-content">
        <h1>Beérkezett üzenetek</h1>
  

        <?php if (empty($uzenetek)): ?>
            <p>Nincs még beérkezett üzenet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Feladó</th>
                        <th>Email</th>
                        <th>Tárgy</th>
                        <th>Dátum</th>
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uzenetek as $sor): ?>
                        <tr>
                            <td><?php echo $sor['id']; ?></td>
                            <td><?php echo htmlspecialchars($sor['ugyfel_nev']); ?></td>
                            <td><?php echo htmlspecialchars($sor['ugyfel_email']); ?></td>
                            <td><?php echo htmlspecialchars($sor['targy']); ?></td>
                            <td><?php echo $sor['letrehozva']; ?></td>
                            <td>
                                <a href="uzenetek_reszletek.php?id=<?php echo $sor['id']; ?>" class="btn-open">Megnyitás</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>
