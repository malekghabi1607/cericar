/**
 * AJAX - Modifier un voyage
 * Bandeau de notification comme la recherche
 */
$(document).ready(function () {
    function submitVoyageUpdate(form) {
        const $form = $(form);
        const profileUrl = $form.data("profile-url");

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

                const targetUrl = response.redirect || profileUrl;
                if (targetUrl) {
                    const separator = targetUrl.indexOf("?") === -1 ? "?" : "&";
                    $.get(targetUrl + separator + "partial=1")
                        .done(function (html) {
                            const $container = $(".voyage-create-page");
                            if ($container.length) {
                                $container.replaceWith(html);
                            } else {
                                $("#page-content").html(html);
                            }
                            if (history && history.pushState) {
                                history.pushState(null, "", targetUrl);
                            }
                        });
                }

                setTimeout(function () {
                    $("#notification-bar").fadeOut(300);
                }, 5000);
            },
            error: function () {
                $("#notification-bar")
                    .removeClass()
                    .addClass("notification-bar error")
                    .text("Une erreur est survenue lors de la modification du voyage.")
                    .fadeIn(200);
            }
        });
    }

    function handleVoyageUpdateSubmit(event) {
        event.preventDefault();
        submitVoyageUpdate(this);
        return false;
    }

    $(document).on("beforeSubmit", "#voyage-update-form", handleVoyageUpdateSubmit);
    $(document).on("submit", "#voyage-update-form", function (event) {
        if ($(this).data("yiiActiveForm")) {
            return;
        }
        return handleVoyageUpdateSubmit.call(this, event);
    });
});
