<?php
/* =============================================================
   contact.php – Kapcsolatfelvételi űrlap feldolgozója
   =============================================================
   Működési folyamat:
     1. POST kérés érkezik az index.html űrlapjából
     2. Honeypot ellenőrzés (spam-szűrés)
     3. Mezők validálása (üres mezők, e-mail formátum)
     4. E-mail összeállítása és elküldése
     5. Adatbázisba mentés
     6. Visszairányítás az index.html-re státusszal:
          index.html?sent=1  → sikeres küldés
          index.html?error=1 → hiba
   ============================================================= */

/* -------------------------------------------------------------
   KONFIGURÁCIÓ – ide kerül a fogadó e-mail cím
   ------------------------------------------------------------- */
$to = 'monikajobs22@gmail.com';

/* kapcsolódás az adatbázishoz */
require_once "db_connect.php";

/* -------------------------------------------------------------
   SEGÉDFÜGGVÉNY: mezők tisztítása
   - trim()              → levágja a szóközöket a szöveg elejéről/végéről
   - strip_tags()        → eltávolítja a HTML tageket (XSS védelem)
   ------------------------------------------------------------- */
function clean($v) {
    return trim(strip_tags($v));
}

/* -------------------------------------------------------------
   CSAK POST KÉRÉST FOGADUNK
   Ha valaki közvetlenül nyitja meg a contact.php-t böngészőből,
   visszairányítjuk a főoldalra.
   ------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

/* -------------------------------------------------------------
   HONEYPOT ELLENŐRZÉS (spam-szűrés)
   Az űrlapban van egy CSS-sel elrejtett "address" nevű mező.
   Valódi felhasználó nem látja, ezért nem tölti ki.
   Ha mégis van benne tartalom → robot küldte → eldobjuk.
   ------------------------------------------------------------- */
if (!empty($_POST['address'])) {
    header('Location: index.html?error=1');
    exit;
}


/* -------------------------------------------------------------
   MEZŐK BEOLVASÁSA ÉS TISZTÍTÁSA
   isset() ellenőrzés: ha a mező nem létezik, üres stringet kapunk
   ------------------------------------------------------------- */
$name    = isset($_POST['name'])    ? clean($_POST['name'])    : '';
$email   = isset($_POST['email'])   ? clean($_POST['email'])   : '';
$message = isset($_POST['message']) ? clean($_POST['message']) : '';


/* -------------------------------------------------------------
   VALIDÁLÁS
   - Egyik mező sem lehet üres
   - Az e-mail cím formátumát a PHP beépített szűrője ellenőrzi
   ------------------------------------------------------------- */
if (
    empty($name)    ||
    empty($email)   ||
    empty($message) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: index.html?error=1');
    exit;
}

/* -------------------------------------------------------------
   E-MAIL ÖSSZEÁLLÍTÁSA
   ------------------------------------------------------------- */
$subject = 'Új üzenet a Star Design weboldalról';

// E-mail törzs: sima szöveges formátum (text/plain)
$body  = "Név: {$name}\n";
$body .= "E-mail: {$email}\n\n";
$body .= "Üzenet:\n{$message}\n";

// Fejlécek
$headers  = "From: Star Design <noreply@star-design.hu>\r\n";
$headers .= "Reply-To: {$name} <{$email}>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";


/* -------------------------------------------------------------
   E-MAIL KÜLDÉSE
   ------------------------------------------------------------- */
$sent = mail($to, $subject, $body, $headers);

/* -------------------------------------------------------------
   VISSZAIRÁNYÍTÁS AZ EREDMÉNY ALAPJÁN
   ADATBÁZIS MENTÉS - try-catch = biztonsági háló (try → futtatod a kódot,catch → elkapod a hibát)
   ------------------------------------------------------------- */
try {

    $stmt = $pdo->prepare("INSERT INTO uzenetek 
        (ugyfel_nev, ugyfel_email, targy, uzenet, letrehozva) 
        VALUES (?, ?, ?, ?, NOW())");

    $stmt->execute([
        $name,
        $email,
        'Kapcsolati űrlap üzenet',
        $message
    ]);

    // EMAIL (nem kötelező a sikerhez)
    mail($to, $subject, $body, $headers);

    // SIKER → mert DB mentés sikeres volt
    header('Location: index.html?sent=1');
    exit;

} catch (Exception $e) {

    // CSAK ha DB hiba van!
    header('Location: index.html?error=1');
    exit;
}
