/* =============================================================
   script.js – Főoldal interaktív viselkedése
   =============================================================
   Két fő funkció:
     1. Sima görgetés (smooth scroll) a navigációs menüpontoknál
     2. Visszajelzés megjelenítése az URL paraméter alapján,
        miután a contact.php feldolgozta az űrlapot
   ============================================================= */

document.addEventListener('DOMContentLoaded', function () {

/* -----------------------------------------------------------
     HAMBURGER MENÜ TOGGLE
     Kattintásra .open osztály kerül a gombra és a nav-ra:
       - gomb: 3 vonalból X lesz (CSS animáció)
       - nav: display:none → display:flex (lenyílik)
     Menüpont kattintásakor automatikusan bezárul.
     ----------------------------------------------------------- */
  var hamburger = document.getElementById('hamburger');
  var mainNav   = document.getElementById('main-nav');

  if (hamburger && mainNav) {

    /* Gomb kattintás: nyit / zár */
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('open');
      mainNav.classList.toggle('open');
    });

    /* Ha egy menüpontra kattintanak, a menü bezárul */
    mainNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('open');
        mainNav.classList.remove('open');
      });
    });

    /* Ha az oldalon kívülre kattintanak, bezárul */
    document.addEventListener('click', function (e) {
      if (!hamburger.contains(e.target) && !mainNav.contains(e.target)) {
        hamburger.classList.remove('open');
        mainNav.classList.remove('open');
      }
    });
  }

  /* -----------------------------------------------------------
     1. SIMA GÖRGETÉS A NAVIGÁCIÓS MENÜPONTOKRA
     ----------------------------------------------------------- */
  document.querySelectorAll('a[data-target]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault(); // leállítja az azonnali ugrást

      var targetId = link.getAttribute('data-target');
      var targetEl = document.getElementById(targetId);
      if (!targetEl) return; // ha nincs ilyen szekció, kilép

      // Fejléc magasságának mérése (dinamikusan, nem fix érték)
      var headerHeight = document.querySelector('.site-header').offsetHeight;

      // Célpont pozíciója az oldal tetejétől mérve
      var scrollTop = targetEl.getBoundingClientRect().top
                      + window.pageYOffset
                      - headerHeight;

      // Animált görgetés
      window.scrollTo({ top: scrollTop, behavior: 'smooth' });
    });
  });


  /* -----------------------------------------------------------
     2. VISSZAJELZÉS AZ ŰRLAP KÜLDÉSE UTÁN
     -----------------------------------------------------------
     A contact.php az eredménytől függően visszairányít:
       index.html?sent=1  → sikeres küldés
       index.html?error=1 → hiba történt
     ----------------------------------------------------------- */
  var params = new URLSearchParams(window.location.search);
  var msgBox = document.getElementById('form-message');

  if (!msgBox) return; // ha nincs ilyen elem, kilép

  if (params.get('sent') === '1') {
    // Sikeres küldés: zöld üzenet
    msgBox.innerHTML = '<div class="success">Köszönöm! Az üzenetedet elküldtük. Hamarosan válaszolok!</div>';
    // Az URL tisztítása (hogy F5-re ne küldje újra az üzenetet)   
    window.history.replaceState({}, document.title, window.location.pathname);
    // Automatikusan legörget a kapcsolat szekcióhoz, hogy az üzenet látszódjon
    setTimeout(function() {
      var contactSection = document.getElementById('contact');
      if (contactSection) {
        var headerHeight = document.querySelector('.site-header').offsetHeight;
        window.scrollTo({ top: contactSection.offsetTop - headerHeight, behavior: 'smooth' });
      }
    }, 100);

  } else if (params.get('error') === '1') {
    // Hiba: piros üzenet
    msgBox.innerHTML = '<div class="error">Hiba történt az üzenet küldése közben. Kérlek próbáld újra, vagy írj közvetlenül: monikajobs22@gmail.com</div>';
    // Az URL tisztítása
      window.history.replaceState({}, document.title, window.location.pathname);
    // Automatikusan legörget a kapcsolat szekcióhoz
    setTimeout(function() {
      var contactSection = document.getElementById('contact');
      if (contactSection) {
        var headerHeight = document.querySelector('.site-header').offsetHeight;
        window.scrollTo({ top: contactSection.offsetTop - headerHeight, behavior: 'smooth' });
      }
    }, 100);
  }

});
