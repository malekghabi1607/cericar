/**
 * AJAX - Proposer un voyage
 * Bandeau de notification comme la recherche
 */
$(document).ready(function () {
    function submitVoyageCreate(form) {
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
                    .text("Une erreur est survenue lors de la proposition du voyage.")
                    .fadeIn(200);
            }
        });
    }

    function handleVoyageCreateSubmit(event) {
        event.preventDefault();
        submitVoyageCreate(this);
        return false;
    }

    $(document).on("beforeSubmit", "#voyage-create-form", handleVoyageCreateSubmit);
    $(document).on("submit", "#voyage-create-form", function (event) {
        if ($(this).data("yiiActiveForm")) {
            return;
        }
        return handleVoyageCreateSubmit.call(this, event);
    });
});
