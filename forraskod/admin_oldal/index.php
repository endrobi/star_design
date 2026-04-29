
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">

    <!-- Rögzített fejléc -->
    <!-- Header sáv -->
    <header class="header-strip">
      <img src="img/logo-fejlec.png" alt="Star Design" />
      <link rel="stylesheet" href="/ADMIN_OLDAL/style.css">
    </header>

    <meta charset="UTF-8">
    <title>Beléptető</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #516588;
            font-family: Arial, sans-serif;
        }
        /* Fejléc: fixen fent marad */
/* Header sáv (csak díszítés) */
        .header-strip {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            background: #F39C12;
            height: 60px;
            display: flex;
            align-items: center;
           justify-content: center;   
          }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
                    <?php 
if (isset($_SESSION['error'])) {
    echo '<p style="color: #e74c3c; text-align: center; font-weight: bold;">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']); // Megjelenítés után töröljük, hogy frissítésnél ne maradjon ott
}
?>
        }



        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #516588;
            box-sizing: border-box;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color:#f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #516588;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Belépés</h2>
    <form action="beleptet.php" method="post">
        <input type="text" name="fh" id="fh" placeholder="Felhasználónév" required>
        <input type="password" name="jl" id="jl" placeholder="Jelszó" required>
        <button type="submit">Belépés</button>
    </form>
</div>

</body>
</html>