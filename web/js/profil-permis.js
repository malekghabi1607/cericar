/**
 * AJAX - Enregistrer permis
 * Bandeau de notification comme la recherche
 */
$(document).ready(function () {
    $(document).on("submit", "#permit-form", function (event) {
        event.preventDefault();

        const $form = $(this);

        $.ajax({
            url: $form.attr("action"),
            type: "POST",
            data: $form.serialize(),
            dataType: "json",
            success: function (response) {
                $("#notification-bar")
                    .removeClass()
                    .addClass("notification-bar " + (response.type || "info"))
                    .text(response.message || "")
                    .fadeIn(200);

                setTimeout(function () {
                    $("#notification-bar").fadeOut(300);
                }, 5000);
            },
            error: function () {
                $("#notification-bar")
                    .removeClass()
                    .addClass("notification-bar error")
                    .text("Une erreur est survenue lors de l'enregistrement du permis.")
                    .fadeIn(200);
            }
        });
    });
});
