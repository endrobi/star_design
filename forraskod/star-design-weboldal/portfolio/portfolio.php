<?php
// Adatbázis kapcsolat (idemásolod a db_connect.php tartalmát vagy require)

require_once "../db_connect.php"; 
require_once "gallery-component.php";

?>

<!doctype html>
<html lang="hu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portfólió</title>
    <link rel="stylesheet" href="styl.css? V=6" />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
     /* =============================================
         SZEKCIÓK SCROLL-MARGÓJA
         Amikor a főoldalról egy szekciócímkére ugrik
         (pl. portfolio.html#kituzok), ez biztosítja,
         hogy a cím ne bújjon a rögzített fejléc alá.
         ============================================= */
    <style>
      #kituzok,
      #logok,
      #polomintak,
      #plakatok-meghivok {
        scroll-margin-top: 100px; /* A header + extra távolság. Ez arra kell hogy amikor a főoldalról átjövünk szépen nézzen ki a cim */
      }
    </style>
  </head>
  <body>
     <!-- =============================================
         VISSZA GOMB (bal felső, rögzített)
         Visszavisz a főoldalra.
         ============================================= -->

    <a href="../index.html" class="back-button"> ← Vissza a főoldalra </a>

   <!-- =============================================
         INFO IKON (jobb alsó sarok, rögzített)
         ============================================= -->
    <a href="../index.html#contact" class="info-icon">
      <img src="kituzok.img/info-icon.png" alt="Információ" />
    </a>

  
    <!-- =============================================
         FEJLÉC SÁV 
     ============================================= -->
    <header class="header-strip">
      <img src="kituzok.img/logo-fejlec.png" alt="Star Design" />
    </header>

    <main>
    <!-- =============================================
           KITŰZŐK SZEKCIÓ
         ============================================= -->

      <section id="kituzok">
        <div class="kituzok">
          <div class="kituzok-background"></div>
          <h2 class="kituzok-title">Kitűzők</h2>
        </div>

        <div class="kituzok-content">
          <div class="kituzok-grid">
            
            <div class="kituzok-item img bal">
              <img src="kituzok.img/group1.png" alt="group1" />
            </div>
            <div class="kituzok-item">
              <img src="kituzok.img/group2.png" alt="group2" />
            </div>
            <div class="kituzok-item jobb">
              <img src="kituzok.img/group3.png" alt="group3" />
            </div>
      
      <!-- DINAMIKUS KÉPEK -->
     <?php renderGallery($pdo, 'kituzok.img', 'kituzok-item'); ?>
          </div>
        </div>

      </section>

   <!-- =============================================
           LOGÓK SZEKCIÓ
        ============================================= -->

      <section id="logok">
        <div class="logok">
          <div class="logok-background"></div>
          <h2 class="logok-title">Logók</h2>
        </div>

        <div class="logok-content">
          <div class="logok-grid">
            <div class="logok-item">
              <img src="logok.img/logo1.png" alt="logo1" />
            </div>
            <div class="logok-item">
              <img src="logok.img/logo2.png" alt="logo2" />
            </div>
            <div class="logok-item">
              <img src="logok.img/logo3.png" alt="logo3" />
            </div>
            <div class="logok-item">
              <img src="logok.img/logo4.png" alt="logo4" />
            </div>
        </div>
          
          <!-- Alsó 2 nagy kép -->
          <div class="logok-grid-large">
            <div class="logok-item-large">
              <img src="logok.img/group1.png" alt="group1" />
            </div>
            <div class="logok-item-large">
              <img src="logok.img/group2.png" alt="group2" />
            </div>
    <!-- INNENTŐL JÖNNEK AZ ÚJONNAN FELTÖLTÖTT KÉPEK AZ ADATBÁZISBÓL -->
      <?php renderGallery($pdo, 'logok.img', 'logok-item'); ?>
          </div>
        </div>
      </section>

    <!-- =============================================
           PÓLÓMINTÁK SZEKCIÓ
           ============================================= -->
      <section id="polomintak">
        <div class="polomintak">
          <div class="polomintak-background"></div>
          <h2 class="polomintak-title">Pólóminták</h2>
        </div>

        <div class="polomintak-content">
          <div class="polomintak-grid">
            <div class="polomintak-item">
              <img src="polomintak.img/group1.png" alt="group1" />
            </div>
            <div class="polomintak-item">
              <img src="polomintak.img/group2.png" alt="group2" />
            </div>
            <div class="polomintak-item">
              <img src="polomintak.img/group3.png" alt="group3" />
            </div>
          </div>

          <!-- Második sor 2 nagy kép -->
          <div class="polomintak-grid-large">
            <div class="polomintak-item-large">
              <img src="polomintak.img/group4.png" alt="group4" />
            </div>
            <div class="polomintak-item-large">
              <img src="polomintak.img/group5.png" alt="group5" />
            </div>
          </div>

          <!-- Harmadik sor 5  kép -->
          <div class="polomintak-grid-five">
            <div class="polomintak-item">
              <img src="polomintak.img/group6.png" alt="group6" />
            </div>
            <div class="polomintak-item">
              <img src="polomintak.img/group7.png" alt="group7" />
            </div>
            <div class="polomintak-item">
              <img src="polomintak.img/group8.png" alt="group8" />
            </div>
            <div class="polomintak-item">
              <img src="polomintak.img/group9.png" alt="group9" />
            </div>
            <div class="polomintak-item">
              <img src="polomintak.img/group10.png" alt="group10" />
            </div>
          </div>
          
          <!-- Negyedik sor 1 nagy kép -->
          <div class="polomintak-grid-single">
            <div class="polomintak-item-large">
              <img src="polomintak.img/group11.png" alt="group11" />
            </div>
          
      <!-- DINAMIKUS KÉPEK -->
     <?php renderGallery($pdo, 'polomintak.img', 'polomintak-item'); ?>
          </div>
        </div>
   </section>

      <!-- =============================================
           PLAKÁTOK – MEGHÍVÓK SZEKCIÓ
           ============================================= -->
     
      <section id="plakatok-meghivok">
        <div class="plakatok-meghivok">
          <div class="plakatok-meghivok-background"></div>
          <h2 class="plakatok-meghivok-title">Plakátok - meghívók</h2>
        </div>

        <div class="plakatok-meghivok-content">
          <div class="plakatok-meghivok-grid">
            <div class="plakatok-meghivok-item img">
              <img src="plakatok-meghivok.img/img1.png" alt="img1" />
            </div>
            <div class="plakatok-meghivok-item">
              <img src="plakatok-meghivok.img/img2.png" alt="img2" />
            </div>
            <div class="plakatok-meghivok-item">
              <img src="plakatok-meghivok.img/img3.png" alt="img3" />
            </div>
            <div class="plakatok-meghivok-item">
              <img src="plakatok-meghivok.img/img4.png" alt="img4" />
            </div>

        <!-- DINAMIKUS KÉPEK -->
      <?php renderGallery($pdo, 'plakatok-meghivok.img', 'plakatok-meghivok-item'); ?>
          </div>

          <!-- Második sor 2  kép -->
          <div class="plakatok-meghivok-grid-two">
            <div class="plakatok-meghivok-item-two">
              <img src="plakatok-meghivok.img/img5.png" alt="img5" />
            </div>
            <div class="plakatok-meghivok-item-two">
              <img src="plakatok-meghivok.img/img6.png" alt="img6" />
            </div>
          
   
          </div>
        </div>
      </section>
    </main>
  </body>
</html>
