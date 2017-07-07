/* autocomplete species on new Observation (this is the main feature) */
(function () {
    var options = {
        url_list: $('#url-list').attr('href'),
        url_get: $('#url-get').attr('href'),
        otherOptions: {
            minimumInputLength: 1,
            theme: 'boostrap',
            formatNoMatches: 'aucune référence trouvée.',
            formatSearching: 'Recherche ...',
            formatInputTooShort: 'Entrez au moins un caractère pour avoir des suggestions'
        }
    };

    $('#observation_bird').autocompleter(options);


}());
