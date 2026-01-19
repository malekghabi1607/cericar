/**
 * AJAX - Inscription (register)
 * Bandeau de notification comme la recherche
 */
$(document).ready(function () {
    $("#register-form").on("submit", function (event) {
        event.preventDefault();

        const $form = $(this);
        const formData = new FormData(this);

        $.ajax({
            url: $form.attr("action"),
            type: "POST",
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
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
                    .text("Une erreur est survenue lors de l'inscription.")
                    .fadeIn(200);
            }
        });
    });
});
