<?php
session_start();
require_once "db_connect.php"; // Itt jön létre a $pdo kapcsolat

// Csak belépett admin láthatja
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
    <title>Üzenet rendszer</title>
    <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
    
</head>
<body>
    
  <div class="header-strip">
      <div class="header-content">
       
           <a href="dashboard.php" class="btn">← Vissza az irányítópulthoz</a>
           <a href="kijelentkezes.php" class="logout-btn">Kijelentkezés</a> 
      </div>    
    </div>

    <div class="main">
        <div class="top-buttons">
            <h1 style="margin-left: 20px;">Beérkezett üzenetek</h1>
        </div>

        <div id="messageList" style="padding: 40px;">
            <table border="2" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f4f4f4;">
                        <th>Státusz</th>
                        <th>Feladó</th>
                        <th>E-mail</th>
                        <th>Tárgy</th>
                        <th>Dátum</th>
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lekérjük az összes üzenetet, a legfrissebb legyen legfelül
                    $stmt = $pdo->query("SELECT * FROM uzenetek ORDER BY letrehozva DESC");
                    $uzenetek = $stmt->fetchAll();

                    if (count($uzenetek) > 0) {
                        foreach ($uzenetek as $row) {
                            // Megnézzük, van-e már megjegyzés vagy dátum mentve
                            $pipa = ($row['feldolgozva_ekkor']) ? "✅" : "⏳";
                            
                            echo "<tr>";
                            echo "<td style='text-align:center;'>" . $pipa . "</td>";
                            echo "<td>" . htmlspecialchars($row['ugyfel_nev']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ugyfel_email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['targy']) . "</td>";
                            echo "<td>" . $row['letrehozva'] . "</td>";
                            echo "<td><a href='uzenetek_reszletek.php?id=" . $row['id'] . "' class='open-btn' style='padding: 5px 10px; background: #007bff; color:white; text-decoration:none; border-radius:3px;'>Megnyitás</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>Nincsenek üzenetek az adatbázisban.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

