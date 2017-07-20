/**
 * Created by leo on 20/07/17.
 */
$(function() {
    $('#observation_save').click(function () {
        var btn = $(this);
        $(btn).buttonLoader('start');
        $(btn).after('<div>Veuillez ne pas recharger la page, votre observation est envoyée sur notre serveur.</div>');
    });
});