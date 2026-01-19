/**
 * AJAX - Profil edit
 * Bandeau de notification comme la recherche
 */
$(document).ready(function () {
    if (window.__profilEditInit) {
        return;
    }
    window.__profilEditInit = true;

    console.log("profil-edit.js chargé.");
    function submitProfilEdit(form) {
        const $form = $(form);
        const formData = new FormData(form);
        const profileUrl = $form.data("profile-url");

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

                if (response.updated && response.updated.name) {
                    $("#profile-name").text(response.updated.name);
                    $(".js-profile-name").text(response.updated.name);
                }

                if (profileUrl) {
                    const separator = profileUrl.indexOf("?") === -1 ? "?" : "&";
                    $.get(profileUrl + separator + "partial=1")
                        .done(function (html) {
                            const $container = $(".profile-edit-page");
                            if ($container.length) {
                                $container.replaceWith(html);
                            } else {
                                $("#page-content").html(html);
                            }
                            if (history && history.pushState) {
                                history.pushState(null, "", profileUrl);
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
                    .text("Une erreur est survenue lors de la mise a jour.")
                    .fadeIn(200);
            }
        });
    }

    function handleProfilEditSubmit(event) {
        event.preventDefault();
        submitProfilEdit(this);
        return false;
    }

    $(document).on("beforeSubmit", "#profil-edit-form", handleProfilEditSubmit);
    $(document).on("submit", "#profil-edit-form", function (event) {
        if ($(this).data("yiiActiveForm")) {
            return;
        }
        return handleProfilEditSubmit.call(this, event);
    });
});
