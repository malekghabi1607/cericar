/**
 * AJAX - Actions profil (reservations + voyages)
 * Bandeau de notification comme la recherche
 */
$(document).ready(function () {
    $(document).off("click.yii", ".js-ajax-link");

    $(document).on("click", ".js-ajax-link", function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        const $link = $(this);
        const confirmMsg = $link.data("confirm");
        if (confirmMsg && !window.confirm(confirmMsg)) {
            return;
        }

        const url = $link.attr("href");
        const method = ($link.data("method") || "post").toUpperCase();
        const csrf = $('meta[name="csrf-token"]').attr("content");
        const data = method === "POST" ? { _csrf: csrf } : {};

        function buildVoyageUrl(currentUrl, action) {
            if (currentUrl.indexOf("r=voyage/") !== -1) {
                return currentUrl.replace(/r=voyage\/[^&]+/, "r=voyage/" + action);
            }
            return currentUrl.replace(/voyage\/[^?&]+/, "voyage/" + action);
        }

        $.ajax({
            url: url,
            type: method,
            dataType: "json",
            data: data,
            success: function (response) {
                $("#notification-bar")
                    .removeClass()
                    .addClass("notification-bar " + (response.type || "info"))
                    .text(response.message || "")
                    .fadeIn(200);

                setTimeout(function () {
                    $("#notification-bar").fadeOut(300);
                }, 5000);

                const currentUrl = window.location.href;
                const profileUrl = response.redirect || (currentUrl.indexOf("site/profil") !== -1 ? currentUrl : null);

                if (
                    profileUrl &&
                    profileUrl.indexOf("site/profil") !== -1 &&
                    $(".profile-page").length === 0
                ) {
                    const separator = profileUrl.indexOf("?") === -1 ? "?" : "&";
                    $.get(profileUrl + separator + "partial=1")
                        .done(function (html) {
                            const $container = $(".profile-page");
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

                const $row = $link.closest("tr");
                if (url.indexOf("voyage/delete") !== -1) {
                    const reactivateUrl = buildVoyageUrl(url, "reactivate");
                    const forceDeleteUrl = buildVoyageUrl(url, "force-delete");
                    const $actions = $row.find(".actions");
                    $row.addClass("is-cancelled");
                    const $cells = $row.find("td");
                    if ($cells.length >= 2) {
                        const $trajetCell = $cells.eq(1);
                        if ($trajetCell.find(".js-cancelled-note").length === 0) {
                            $trajetCell.append('<div class="muted js-cancelled-note">Trajet annule par vous</div>');
                        }
                    }
                    $actions.html(
                        '<a class="btn-reactivate js-ajax-link" title="Revalider" aria-label="Revalider" data-method="post" data-confirm="Revalider ce voyage ?" href="' +
                            reactivateUrl +
                            '"><i class="fa-solid fa-rotate-right"></i></a>' +
                            '<a class="btn-delete js-ajax-link" title="Supprimer definitivement" aria-label="Supprimer definitivement" data-method="post" data-confirm="Supprimer definitivement ce voyage et ses reservations ?" href="' +
                            forceDeleteUrl +
                            '"><i class="fa-solid fa-trash"></i></a>'
                    );
                    if ($cells.length >= 2) {
                        $cells.eq($cells.length - 2).text("ANNULE PAR LE CONDUCTEUR.");
                    }
                } else if (url.indexOf("voyage/reactivate") !== -1) {
                    const updateUrl = buildVoyageUrl(url, "update");
                    const deleteUrl = buildVoyageUrl(url, "delete");
                    const $actions = $row.find(".actions");
                    $row.removeClass("is-cancelled");
                    $actions.html(
                        '<a class="icon-btn" title="Editer" aria-label="Editer" href="' +
                            updateUrl +
                            '"><i class="fa-solid fa-pen"></i></a>' +
                            '<a class="icon-btn danger js-ajax-link" title="Supprimer" aria-label="Supprimer" data-method="post" data-confirm="Supprimer ce voyage ?" href="' +
                            deleteUrl +
                            '"><i class="fa-solid fa-trash"></i></a>'
                    );
                    const $cells = $row.find("td");
                    if ($cells.length >= 2) {
                        $cells.eq($cells.length - 2).text("Aucune");
                        $cells.eq(1).find(".js-cancelled-note").remove();
                    }
                } else if (url.indexOf("voyage/force-delete") !== -1 || url.indexOf("reservation/delete") !== -1) {
                    $row.fadeOut(200, function () {
                        $(this).remove();
                    });
                }

                if (profileUrl && $(".profile-page").length) {
                    const separator = profileUrl.indexOf("?") === -1 ? "?" : "&";
                    $.get(profileUrl + separator + "partial=1")
                        .done(function (html) {
                            $(".profile-page").replaceWith(html);
                            if (history && history.pushState) {
                                history.pushState(null, "", profileUrl);
                            }
                        });
                }
            },
            error: function () {
                $("#notification-bar")
                    .removeClass()
                    .addClass("notification-bar error")
                    .text("Une erreur est survenue lors de l'action.")
                    .fadeIn(200);
            }
        });
    });
});
