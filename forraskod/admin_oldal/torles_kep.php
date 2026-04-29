<?php
session_start();
require_once "db_connect.php";

// védelem
if (!isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Nincs ID megadva!");
}

$id = $_GET['id'];

// 1. lekérjük a képet
$stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
$stmt->execute([$id]);
$kep = $stmt->fetch();

if (!$kep) {
    die("Kép nem található!");
}

// 2. fájl törlése a szerverről
$file_path = "../star-design-weboldal/" . $kep['file_path'];

if (file_exists($file_path)) {
    unlink($file_path);
}

// 3. törlés adatbázisból
$stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
$stmt->execute([$id]);

// 4. visszairányítás
header("Location: dashboard.php");
exit();