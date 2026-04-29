<?php
session_start();
session_unset();    // Minden adat törlése a munkamenetből
session_destroy();  // A munkamenet teljes megsemmisítése
header("Location: index.php"); // Vissza a belépőhöz
exit();
?>
