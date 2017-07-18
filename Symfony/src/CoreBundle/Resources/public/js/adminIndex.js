/**
 * Created by leo on 18/07/17.
 */
$(function() {
    var $incre = 0;
    var $window = $(window);

        $window.on('scroll', function () {
            if (($(window).scrollTop() > $(document).height() - $(window).height() - $('footer').height() - 20) && ($(window).scrollTop() < $(document).height() - $(window).height() - $('footer').height() + 20)) {
                $incre = $incre + 1;
                var submit = function () {
                    var $moreObservationUrl = "/admin/more/" + $incre;
                    return $.ajax({
                        url: $moreObservationUrl,
                        method: 'GET'
                    }).done(function (response) {
                        $.each(response.observations, function (key, value) {
                            //Get date
                            var date = new Date(value.date.date);
                            date = (date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear());
                            //Get user picture
                            var $gravatarUrl = "https://www.gravatar.com/avatar/" + md5(value.user.email) + "?default=" + encodeURIComponent('https://leogrambert.fr/front/projets/blogEcrivain/blog/web/img/user.png');
                            //Get user roles
                            var $role = value.user.roles;
                            var $roleString = "";
                            if ($.inArray("ROLE_ADMIN", $role)) {
                                if ($.inArray("ROLE_PRO", $role)) {
                                    $roleString = 'Amateur';
                                } else {
                                    $roleString = 'Naturaliste';
                                }
                            } else {
                                $roleString = 'Administrateur';
                            }
                            //Get user xp
                            var $xp = value.user.xp;
                            var $xpHtml = "";
                            if ($xp >= 500 && $xp < 5000) {
                                $xpHtml = "<img class='imgXpObservation' src='/bundles/core/img/bronze.png' alt='trophee-bronze'>";
                            } else if ($xp >= 5000 && $xp < 10000) {
                                $xpHtml = "<img class='imgXpObservation' src='/bundles/core/img/argent.png' alt='trophee-argent'>";
                            } else if ($xp >= 10000) {
                                $xpHtml = "<img class='imgXpObservation' src='/bundles/core/img/or.png' alt='trophee-or'>";
                            }
                            //Get facebook share button
                            var $btnFacebook = '<div class="fb-share-button" data-href="https://leogrambert.fr/front/projets/nosAmisLesOiseaux/Symfony/web/sheet/' + value.id + '" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fleogrambert.fr%2Ffront%2Fprojets%2FnosAmisLesOiseaux%2FSymfony%2Fweb%2Fsheet%2F' + value.id + '&amp;src=sdkpreparse">Partager</a></div>';
                            //Display observation sheet
                            if (value.picture !== null) {
                                $('#moreObservation').before(
                                    '<div class="col-md-5 col-xs-12 observationContainer">' +
                                    '<div class="row layer">' +
                                    '<div class="sheet">' +
                                    '<div class="col-xs-4">' +
                                    '<img class="profilePicture" src="' + $gravatarUrl + '" alt="profilePicture"/> ' +
                                    '</div>' +
                                    '<div class="col-xs-8">' +
                                    '<p class="user">' + value.user.username + '</p>' +
                                    $btnFacebook +
                                    '<p class="role">' + $roleString + '</p>' +
                                    $xpHtml +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="row contain ajax">' +
                                    '<div class="col-xs-6">' +
                                    '<p class="link"><a href="' + value.bird.url + '">Lien fiche INPN</a></p>' +
                                    '<a href="/uploads/img/' + value.picture.id + '.' + value.picture.ext + '" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><img class="imgObservation" src="/uploads/img/' + value.picture.id + '.' + value.picture.ext + '" alt="' + value.picture.alt + '" /></a>' +
                                    '</div>' +
                                    '<div class="col-xs-6">' +
                                    '<p class="nameBird">' + value.bird.nomVern + '<br><span class="date">le ' + date + '</span></p>' +
                                    '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                    '<p class="lon">Longitude : ' + value.longitude + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            } else {
                                $('#moreObservation').before(
                                    '<div class="col-md-5 col-xs-12 observationContainer">' +
                                    '<div class="row layer">' +
                                    '<div class="sheet">' +
                                    '<div class="col-xs-4">' +
                                    '<img class="profilePicture" src="' + $gravatarUrl + '" alt="profilePicture"/> ' +
                                    '</div>' +
                                    '<div class="col-xs-8">' +
                                    '<p class="user">' + value.user.username + '</p>' +
                                    $btnFacebook +
                                    '<p class="role">' + $roleString + '</p>' +
                                    $xpHtml +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="row contain ajax">' +
                                    '<div class="col-xs-6">' +
                                    '<p class="link"><a href="' + value.bird.url + '">Lien fiche INPN</a></p>' +
                                    '<img class="imgObservation" src="/bundles/core/img/logo.png" alt="no-picture" />' +
                                    '</div>' +
                                    '<div class="col-xs-6">' +
                                    '<p class="nameBird">' + value.bird.nomVern + '<br><span class="date">le ' + date + '</span></p>' +
                                    '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                    '<p class="lon">Longitude : ' + value.longitude + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            }
                            FB.XFBML.parse();
                        })
                    }).fail(function (response) {
                        $window.off();
                        var msg = '';
                        if (response.status === 422) {
                            msg = 'Vous n\'avez pas d\'autres observations.';
                        } else {
                            msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                        }
                        $('#errorMsg').remove();
                        $('footer').before('<div id="errorMsg" class="alert alert-warning">' + msg + '</div>');
                    })
                };
                submit();
            }
        });
});