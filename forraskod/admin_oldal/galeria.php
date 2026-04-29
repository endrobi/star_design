<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["admin_id"])) { header("Location: index.php"); exit(); }

$uzenet = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto"])) {
    $title = $_POST["title"] ?? 'Névtelen';
    $category = $_POST["category"]; 
    
    // 1. Útvonalak beállítása
    $file_name = time() . "_" . basename($_FILES["foto"]["name"]);
    
    // Ahová a PHP fizikailag másolja a fájlt (adminból ki, weboldalba be)
    $physical_path = "../star-design-weboldal/portfolio/" . $category . "/" . $file_name;

    // Ami az adatbázisba kerül (a főoldal index.php-jához képest)
    $db_path = "portfolio/" . $category . "/" . $file_name;

    // 2. A MÁSOLÁS (Csak egyszer szabad meghívni!)
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $physical_path)) {
        
        // 3. MENTÉS AZ ADATBÁZISBA
        $stmt = $pdo->prepare("INSERT INTO gallery (file_path, title, category) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$db_path, $title, $category])) {
            $uzenet = "<p style='color:green;'>Sikeresen feltöltve a(z) $category mappába!</p>";
        } else {
            $uzenet = "<p style='color:red;'>A fájl a helyére került, de az adatbázisba nem sikerült menteni!</p>";
        }

    } else {
        // Ha nem sikerül, ellenőrizd a mappa nevét: star-design-weboldal (kötőjellel!)
        $uzenet = "<p style='color:red;'>Hiba! Nem sikerült a másolás. Ellenőrizd a mappát: $physical_path</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Portfólió feltöltés</title>
    <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
</head>
<body>

<div class="header-strip">
    <div class="header-content">
        <span>Portfólió feltöltés</span>
        <a href="dashboard.php" class="logout-btn">Vissza</a>
    </div>
</div>

<div class="galeria-page"> 

    <div class="form-container">
        <h2>Új munka hozzáadása</h2>

        <?php if (!empty($uzenet)) echo "<p style='color: green;'>$uzenet</p>"; ?>

        <form action="galeria.php" method="post" enctype="multipart/form-data">

            <label>Kép címe:</label>
            <input type="text" name="title" class="form-input" placeholder="Munka neve..." required>

            <label>Kategória:</label>
            <select name="category" class="form-input" required>
                <option value="kituzok.img">Kitűzők</option>
                <option value="logok.img">Logók</option>
                <option value="plakatok-meghivok.img">Plakátok & Meghívók</option>
                <option value="polomintak.img">Pólóminták</option>
            </select>

            <label>Kép kiválasztása:</label>
            <input type="file" name="foto" class="form-file" required>

            <button type="submit" class="btn-primary">
                Feltöltés a portfólióba
            </button>

        </form>
    </div>

</div>
