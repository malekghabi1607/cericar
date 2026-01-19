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

$(document).on('click', '.btn-reserver', function(e) {
    if ($(this).hasClass('js-reserver-correspondance')) {
        return;
    }
    e.preventDefault();
    
    let btn = $(this);
    let idVoyage = btn.data('voyage');
    let nbPlaces = btn.data('places');
    let reservationBox = btn.closest('.reservation-box'); 

    btn.prop('disabled', true).text('Chargement...');

    $.ajax({
        url: 'index.php?r=voyage/reserver',
        type: 'POST',
        data: {
            voyage_id: idVoyage,
            nb_places: nbPlaces,
            // Jeton de sécurité obligatoire pour le POST
            _csrf: $('meta[name="csrf-token"]').attr('content') 
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Remplacement dynamique par le bloc vert de confirmation
                reservationBox.fadeOut(300, function() {
                    $(this).html(`
                        <div class="success-screen" style="background:#f0fff4; border:1px solid #c6f6d5; padding:20px; border-radius:12px; text-align:center;">
                            <i class="fa-solid fa-circle-check" style="color:#38a169; font-size:30px;"></i>
                            <h4 style="color:#2f855a; margin-top:10px;">Réservation confirmée !</h4>
                            <p>Ce trajet est maintenant enregistré sur votre profil.</p>
                        </div>
                    `).fadeIn(300);
                });
                $('#notification-bar')
                    .removeClass()
                    .addClass('notification-bar success')
                    .text(response.message || 'Reservation enregistree.')
                    .fadeIn(200);
            } else {
                $('#notification-bar')
                    .removeClass()
                    .addClass('notification-bar error')
                    .text(response.message || 'Erreur lors de la reservation.')
                    .fadeIn(200);
                btn.prop('disabled', false).text('RÉSERVER');
            }
        },
        error: function() {
            $('#notification-bar')
                .removeClass()
                .addClass('notification-bar error')
                .text("Erreur critique (500). Vérifiez que fredouil.reservation accepte l'ID " + idVoyage)
                .fadeIn(200);
            btn.prop('disabled', false).text('RÉSERVER');
        }
    });
});

$(document).on('click', '.js-reserver-correspondance', function(e) {
    e.preventDefault();
    e.stopPropagation();

    const $btn = $(this);
    const url = $btn.data('url');
    const voyages = $btn.data('voyages');
    const nbPlaces = $btn.data('places') || 1;

    if (!url || !voyages || !voyages.length) {
        return;
    }

    $btn.prop('disabled', true);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: {
            voyages: voyages,
            nb_places: nbPlaces,
            _csrf: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#notification-bar')
                .removeClass()
                .addClass('notification-bar ' + (response.type || 'info'))
                .text(response.message || '')
                .fadeIn(200);

            setTimeout(function () {
                $('#notification-bar').fadeOut(300);
            }, 5000);

            if (response.status === 'success' || response.type === 'success') {
                const $box = $btn.closest('.correspondance-reservation');
                const $target = $box.length ? $box : $btn.closest('.reservation-box');
                if ($target.length) {
                    $target.html(
                        '<div class="success-screen" style="background:#f0fff4; border:1px solid #c6f6d5; padding:20px; border-radius:12px; text-align:center;">' +
                            '<i class="fa-solid fa-circle-check" style="color:#38a169; font-size:30px;"></i>' +
                            '<h4 style="color:#2f855a; margin-top:10px;">Réservation confirmée !</h4>' +
                            '<p>Ce trajet est maintenant enregistré sur votre profil.</p>' +
                        '</div>'
                    );
                }
            }
        },
        error: function() {
            $('#notification-bar')
                .removeClass()
                .addClass('notification-bar error')
                .text("Une erreur est survenue lors de la reservation.")
                .fadeIn(200);
        },
        complete: function() {
            $btn.prop('disabled', false);
        }
    });
});
