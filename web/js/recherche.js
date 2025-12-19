/**
 * Script AJAX – Recherche de voyages
 * Étape 4 : chargement dynamique sans rechargement de page
 */

$(document).ready(function () {
 // Ce log me permet de vérifier que le script est bien pris en compte par le navigateur. 
    console.log("Script AJAX de recherche chargé.");

    //  Interception de la soumission du formulaire de recherche
    $('form.form-recherche').on('submit', function (event) {

        // Ca empeche le comportement par défaut (rechargement de la page)
        event.preventDefault();
        console.log("Soumission du formulaire interceptée.");
     
        // Récupération du formulaire soumis
        let $form = $(this);
 
          // Envoi de la requête AJAX vers le contrôleur Yii
        $.ajax({
            url: $form.attr('action'),   // URL définie dans l’attribut action du formulaire
            type: 'GET',               // Méthode HTTP identique à celle du formulaire
            data: $form.serialize(),       // Sérialisation des champs du formulaire
            dataType: 'json',           // Le serveur renvoie une réponse JSON

             // En cas de succès
            success: function (response) {

                console.log("Réponse AJAX reçue :", response);

                // Mise à jour dynamique des résultats
                $('#resultats-voyages').html(response.html);

                // Mise à jour du bandeau de notification global (layout)
                $('#notification-bar')
                    .removeClass()
                    .addClass('notification-bar ' + response.type)
                    .text(response.message)
                    .fadeIn(200);
            // on masque automatique après 5 secondes
                setTimeout(function () {
                    $('#notification-bar').fadeOut(300);
                }, 5000);
            },
    // En cas d’erreur AJAX (serveur, réseau, etc.)
            error: function () {

                $('#notification-bar')
                    .removeClass()
                    .addClass('notification-bar error')
                    .text("Une erreur est survenue lors de la communication avec le serveur.")
                    .fadeIn(200);
            }
        });
    });
});