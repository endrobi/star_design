<?php
session_start();
require_once "db_connect.php"; // PDO kapcsolat ($pdo)

if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tervek kezelése</title>
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
        <h1>Beérkezett tervek</h1>

        <div id="planList">
            <table>
                <thead>
                    <tr>
                        <th>Státusz</th>
                        <th>Ügyfél neve</th>
                         <th>Ügyfél email</th>
                        <th>Tervek</th>
                        <th>Dátum</th>
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lekérjük a terveket (feltételezve, hogy 'tervek' a tábla neve)
                    $stmt = $pdo->query("SELECT * FROM tervek ORDER BY letrehozva DESC");
                    $tervek = $stmt->fetchAll();

                    if (count($tervek) > 0) {
                        foreach ($tervek as $sor) {
                            // Itt is megnézzük a feldolgozottságot
                            $pipa = (isset($sor['feldolgozva_ekkor']) && $sor['feldolgozva_ekkor']) ? "✅" : "⏳";
                            
                            echo "<tr>";
                            echo "<td style='text-align:center;'>" . $pipa . "</td>";
                            echo "<td>" . htmlspecialchars($sor['ugyfel_nev']) . "</td>";
                            echo "<td>" . htmlspecialchars($sor['ugyfel_email']) . "</td>";
                            echo "<td>" . htmlspecialchars($sor['design_data'] ?? 'Nincs megadva') . "</td>";
                            echo "<td>" . $sor['letrehozva'] . "</td>";
                            // Itt majd egy terv_reszletek.php-ra mutassunk
                            echo "<td><a href='tervek_reszletek.php?id=" . $sor['id'] . "' class='btn-open'>Megnyitás</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>Nincsenek tervek.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
