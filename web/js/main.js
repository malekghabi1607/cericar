// ===============================
//  Vérifier si le JS charge bien
// ===============================
console.log("main.js chargé avec succès !");


// ===============================
//  OUVRIR LE MENU LATERAL (mobile)
// ===============================
function openMenu() {
    const menu = document.getElementById("sideMenu");
    const overlay = document.getElementById("overlay");

    if (!menu || !overlay) {
        console.error("Erreur : ID sideMenu ou overlay introuvable !");
        return;
    }

    menu.classList.add("open");
    overlay.classList.add("show");
}


// ===============================
//  FERMER LE MENU LATERAL (mobile)
// ===============================
function closeMenu() {
    const menu = document.getElementById("sideMenu");
    const overlay = document.getElementById("overlay");

    if (!menu || !overlay) {
        console.error("Erreur : ID sideMenu ou overlay introuvable !");
        return;
    }

    menu.classList.remove("open");
    overlay.classList.remove("show");
}


// ===============================
//  FERMETURE AUTOMATIQUE en cliquant sur un lien
// ===============================
document.addEventListener("DOMContentLoaded", () => {
    const menuLinks = document.querySelectorAll("#sideMenu a");

    menuLinks.forEach(link => {
        link.addEventListener("click", () => closeMenu());
    });
});

// ===============================
//  La date doit automatiquement afficher “demain” et empêcher de choisir avant
// ===============================
// DATE PAR DEFAUT = DEMAIN
// Sélection du champ
const dateInput = document.getElementById("dateInput");

if (dateInput) {
    // Calcul de demain
    const today = new Date();
    today.setDate(today.getDate() + 1);

    const tomorrow = today.toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
    });

    // On affiche demain
    dateInput.value = tomorrow;

    // Empêche toute saisie clavier
    dateInput.addEventListener("keydown", function (e) {
        e.preventDefault();
    });
}

// --- Animation d'apparition au scroll ---
const animatedElements = document.querySelectorAll('.fadeUp');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('show');
        }
    });
}, {
    threshold: 0.2
});

animatedElements.forEach(el => observer.observe(el));

// ===============================
//  AJAX GLOBAL + BANDEAU
// ===============================
function showNotification(type, message) {
    const $bar = $("#notification-bar");
    if (!$bar.length) {
        return;
    }
    $bar
        .removeClass()
        .addClass("notification-bar " + (type || "info"))
        .text(message || "")
        .fadeIn(200);
    setTimeout(() => {
        $bar.fadeOut(300);
    }, 5000);
}
