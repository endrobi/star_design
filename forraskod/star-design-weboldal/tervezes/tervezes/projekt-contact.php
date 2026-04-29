<?php
/* =============================================================
   projekt-contact.php – Projekt tervező űrlap feldolgozója
   =============================================================
   Különbség a contact.php-tól:
     - JSON választ ad vissza (nem redirect), mert a
       projekt-script-final.js fetch() API-val hívja
     - Képfeltöltést is fogad (inspiráció képek)
     - Részletesebb projekt adatokat kap (kategória, szín, stílus stb.)

   Működési folyamat:
     1. POST kérés érkezik a projekt-script-final.js fetch()-éből
     2. Validálás (kötelező mezők: név, e-mail)
     3. Projekt adatok összegyűjtése és formázása
     4. Feltöltött képek kezelése (opcionális)
     5. E-mail küldése Móninak
     6. JSON válasz: {"success": true, "project_id": "SD2026-XXX"}
                  vagy {"success": false, "message": "Hiba leírása"}
   ============================================================= */

/* -------------------------------------------------------------
   KONFIGURÁCIÓ
   ------------------------------------------------------------- */
$to = 'monikajobs22@gmail.com';

/* Projekt azonosító generálása: SD + évszám + 4 jegyű véletlenszám
   Például: SD2026-4827 */
$project_id = 'SD' . date('Y') . '-' . rand(1000, 9999);


/* -------------------------------------------------------------
   JSON VÁLASZ HELPER FÜGGVÉNYEK
   A fetch() API JSON-t vár vissza, nem HTML-t vagy redirect-et.
   ------------------------------------------------------------- */
function sendSuccess($project_id) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success'    => true,
        'project_id' => $project_id
    ]);
    exit;
}

function sendError($message) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit;
}


/* -------------------------------------------------------------
   SEGÉDFÜGGVÉNY: mezők tisztítása
   ------------------------------------------------------------- */
function clean($v) {
    return trim(strip_tags($v));
}


/* -------------------------------------------------------------
   CSAK POST KÉRÉST FOGADUNK
   ------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Érvénytelen kérés.');
}


/* -------------------------------------------------------------
   KÖTELEZŐ MEZŐK BEOLVASÁSA ÉS VALIDÁLÁSA
   ------------------------------------------------------------- */
$name  = isset($_POST['name'])  ? clean($_POST['name'])  : '';
$email = isset($_POST['email']) ? clean($_POST['email']) : '';

if (empty($name) || empty($email)) {
    sendError('A név és az e-mail megadása kötelező.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendError('Érvénytelen e-mail cím.');
}


/* -------------------------------------------------------------
   OPCIONÁLIS MEZŐK BEOLVASÁSA
   JSON tömbök dekódolása (a JS JSON.stringify()-val küldte)
   ------------------------------------------------------------- */
$phone        = isset($_POST['phone'])        ? clean($_POST['phone'])        : '';
$description  = isset($_POST['description'])  ? clean($_POST['description'])  : '';
$links        = isset($_POST['links'])        ? clean($_POST['links'])        : '';
$notes        = isset($_POST['notes'])        ? clean($_POST['notes'])        : '';
$text_content = isset($_POST['textContent'])  ? clean($_POST['textContent'])  : '';
$has_text     = isset($_POST['hasText'])      ? clean($_POST['hasText'])      : '';

/* JSON tömbök (kategóriák, színek, stílusok, betűtípusok) */
$categories   = isset($_POST['categories'])   ? json_decode($_POST['categories'],   true) : [];
$colors       = isset($_POST['colors'])       ? json_decode($_POST['colors'],       true) : [];
$styles       = isset($_POST['styles'])       ? json_decode($_POST['styles'],       true) : [];
$fav_fonts    = isset($_POST['favoriteFonts'])? json_decode($_POST['favoriteFonts'], true) : [];

/* Biztonsági ellenőrzés: valóban tömbök-e */
if (!is_array($categories)) $categories = [];
if (!is_array($colors))     $colors     = [];
if (!is_array($styles))     $styles     = [];
if (!is_array($fav_fonts))  $fav_fonts  = [];


/* -------------------------------------------------------------
   E-MAIL TÖRZS ÖSSZEÁLLÍTÁSA
   Minden adat szöveges formátumban, Móninak olvashatóan
   ------------------------------------------------------------- */
$body  = "=== ÚJ PROJEKT TERVEZŐ KÉRÉS ===\n";
$body .= "Projekt azonosító: #{$project_id}\n";
$body .= "Beérkezett: " . date('Y. m. d. H:i') . "\n\n";

$body .= "--- KAPCSOLATI ADATOK ---\n";
$body .= "Név:    {$name}\n";
$body .= "E-mail: {$email}\n";
$body .= (!empty($phone)  ? "Telefon: {$phone}\n" : '');
$body .= (!empty($notes)  ? "Megjegyzés: {$notes}\n" : '');

$body .= "\n--- PROJEKT RÉSZLETEK ---\n";
$body .= "Kategória(k): " . (count($categories) > 0 ? implode(', ', $categories) : 'Nincs megadva') . "\n";
$body .= "Stílus(ok):   " . (count($styles)     > 0 ? implode(', ', $styles)     : 'Nincs megadva') . "\n";
$body .= "Szín(ek):     " . (count($colors)      > 0 ? implode(', ', $colors)     : 'Nincs megadva') . "\n";

if ($has_text === 'true' || $has_text === '1') {
    $body .= "\n--- SZÖVEG ---\n";
    $body .= "Szöveg tartalma: {$text_content}\n";
    $body .= "Kedvenc betűtípusok: " . (count($fav_fonts) > 0 ? implode(', ', $fav_fonts) : 'Nincs megadva') . "\n";
}

if (!empty($description)) {
    $body .= "\n--- ELKÉPZELÉS LEÍRÁSA ---\n";
    $body .= "{$description}\n";
}

if (!empty($links)) {
    $body .= "\n--- INSPIRÁCIÓ LINKEK ---\n";
    $body .= "{$links}\n";
}

/* Feltöltött képek számlálása */
$image_count = 0;
foreach ($_FILES as $key => $file) {
    if (strpos($key, 'image_') === 0 && $file['error'] === UPLOAD_ERR_OK) {
        $image_count++;
    }
}
if ($image_count > 0) {
    $body .= "\n--- FELTÖLTÖTT KÉPEK ---\n";
    $body .= "Inspiráció képek száma: {$image_count} db\n";
    $body .= "(Képek nem kerülnek csatolásra – kérlek vedd fel a kapcsolatot a feltöltővel)\n";
}

$body .= "\n=== VÉGE ===\n";


/* -------------------------------------------------------------
   E-MAIL FEJLÉCEK
   From: saját domain (spam szűrő miatt)
   Reply-To: a látogató e-mailje (Válasz gomb ide megy)
   ------------------------------------------------------------- */
$subject  = "Új projekt kérés – #{$project_id} – {$name}";
$headers  = "From: Star Design Tervező <noreply@star-design.hu>\r\n";
$headers .= "Reply-To: {$name} <{$email}>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";


/* -------------------------------------------------------------
   E-MAIL KÜLDÉSE ÉS VÁLASZ VISSZAKÜLDÉSE
   ------------------------------------------------------------- */
$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    sendSuccess($project_id);
} else {
    sendError('Az e-mail küldése sikertelen. Kérlek próbáld újra, vagy írj közvetlenül: monikajobs22@gmail.com');
}
