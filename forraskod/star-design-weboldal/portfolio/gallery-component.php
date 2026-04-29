<?php
function renderGallery($pdo, $category, $itemClass) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE category = ? ORDER BY id DESC");
    $stmt->execute([$category]);

    while ($row = $stmt->fetch()) {
        $clean_path = str_replace("portfolio/", "", $row['file_path']);

        echo '<div class="' . $itemClass . ' dinamikus-kep">';
        echo '<img class="dinamikus-img" src="' . $clean_path . '" alt="' . $row['title'] . '">';
        echo '</div>';
    }
}
?>