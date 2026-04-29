<?php
session_start();
require_once "db_connect.php"; // A PDO-s csatlakozás

// Az űrlapról érkező adatok
$felhasznalo = $_POST["fh"] ?? '';
$beirt_jelszo = $_POST["jl"] ?? '';

// 1. Lekérjük a felhasználót az adatbázisból
$stmt = $pdo->prepare("SELECT id, jelszo, jogkor FROM admin_users WHERE felhasznalonev = ? AND aktiv = '1'");
$stmt->execute([$felhasznalo]);
$user = $stmt->fetch();

// 2. Ellenőrzés: Létezik a felhasználó.ÉS jó a jelszó?
if ($user && password_verify($beirt_jelszo, $user["jelszo"])) {
    
    // SIKERES BELÉPÉS
    session_regenerate_id(true); // Biztonsági frissítés
    $_SESSION["admin_id"] = $user["id"];
    $_SESSION["admin_nev"] = $felhasznalo;
    $_SESSION["jogkor"] = $user["jogkor"]; // Eltároljuk, hogy admin vagy moderator

    header("Location: dashboard.php");
    exit();
} else {
    // SIKERTELEN BELÉPÉS
    $_SESSION["error"] = "Hibás felhasználónév vagy jelszó,vagy azonosítója nem aktív!";
    header("Location: index.php");
    exit();
}
