<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION["admin_id"])) { exit("Hozzáférés megtagadva"); }

if (isset($_GET['id']) && isset($_GET['tipus'])) {
    $id = (int)$_GET['id'];
    $tipus = $_GET['tipus']; // 'uzenetek' vagy 'tervek'

    // Biztonsági ellenőrzés: csak ebből a két táblából engedünk törölni
    $engedelyezett_tablak = ['uzenetek', 'tervek', 'galeria'];
    
    if (in_array($tipus, $engedelyezett_tablak)) {
        $stmt = $pdo->prepare("DELETE FROM $tipus WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    // Visszairányítás oda, ahonnan jöttünk
    header("Location: " . $tipus . ".php?torolve=1");
    exit();
}
