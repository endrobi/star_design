<?php
// HIBÁK LÁTHATÓVÁ TÉTELE (fejlesztéshez!)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// KAPCSOLAT AZ ADATBÁZISHOZ
require_once "../../ADMIN_OLDAL/db_connect.php";

// ADATOK ÁTVÉTELE
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';

$categories = $_POST['categories'] ?? '';
$colors = $_POST['colors'] ?? '';
$styles = $_POST['styles'] ?? '';
$hasText = $_POST['hasText'] ?? '';
$textContent = $_POST['textContent'] ?? '';
$favoriteFonts = $_POST['favoriteFonts'] ?? '';
$description = $_POST['description'] ?? '';
$links = $_POST['links'] ?? '';
$notes = $_POST['notes'] ?? '';

// 🔥 ÖSSZEGYŰJTJÜK EGYBE (JSON)
$design_data = json_encode([
    "categories" => json_decode($categories),
    "colors" => json_decode($colors),
    "styles" => json_decode($styles),
    "hasText" => $hasText,
    "textContent" => $textContent,
    "favoriteFonts" => json_decode($favoriteFonts),
    "description" => $description,
    "links" => $links,
    "notes" => $notes
]);

// ALAP ELLENŐRZÉS
if (!$name || !$email) {
    echo json_encode([
        "success" => false,
        "message" => "Hiányzó név vagy email"
    ]);
    exit;
}

// ADATBÁZISBA MENTÉS
try {
    $sql = "INSERT INTO tervek 
    (ugyfel_nev, ugyfel_email, design_data, letrehozva, allapot) 
    VALUES (?, ?, ?, NOW(), 'uj')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $design_data]);

    echo json_encode([
        "success" => true,
        "project_id" => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Adatbázis hiba: " . $e->getMessage()
    ]);
}