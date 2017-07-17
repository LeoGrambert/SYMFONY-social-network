/**
 * Created by leo on 10/07/17.
 */
$(function(){
    var $birdField = $('#birdField');

    //function to clear
    $('#clear').click(function (e) {
        e.preventDefault();
        location.reload();
    });

    //function to get all observations for a bird
    $birdField.on('change', function(e){
        e.preventDefault();
        //Get bird id
        var $input = $birdField.val();
        var $datalist = $('#birds');
        var $val = $($datalist).find('option[value="'+$input+'"]');
        var $endval = $val.attr('id');
        //Call ajax
        var submit = function(){
            var $birdFieldUrl = '/search/bird/untreated/'+$endval;
            return $.ajax({
                url: $birdFieldUrl,
                method: 'GET'
            }).done(function(response){
                $('#errorMsg').remove();
                $('#results').empty();
                $.each(response.observations, function(key, value){
                    var date = new Date(value.date.date);
                    date = (date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear());
                    //get description
                    var $description = "";
                    if (value.description !== null){
                        $description = "Description donnée : "+value.description;
                    } else {
                        $description = "L'utilisateur n'a pas donné de description pour l'espèce observée."
                    }
                    if(value.picture !== null){
                        $('#results').append(
                            '<p id="observationDescription">"'+value.user.username+'" a observé <strong>"'+value.bird.nomVern+'"</strong> le '+date+' aux coordonnées suivantes : <a title="Cliquez pour accéder à la carte" href="/search/gps/'+value.latitude+'/'+value.longitude+'">'+value.latitude+', '+value.longitude+'</a></p>' +
                            '<p class="link"><a href="value.bird.url">Lien vers la fiche INPN</a></p> ' +
                            '<a href="/uploads/img/'+value.picture.id+'.'+value.picture.ext+'" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><img src="/uploads/img/'+value.picture.id+'.'+value.picture.ext+'" alt="'+value.picture.alt+'" height="200"/></a>' +
                            '<p class="description">'+$description+'</p> '
                        );
                    } else {
                        $('#results').append(
                            '<p id="observationDescription">"'+value.user.username+'" a observé <strong>"'+value.bird.nomVern+'"</strong> le '+date+' aux coordonnées suivantes : <a title="Cliquez pour accéder à la carte" href="/search/gps/'+value.latitude+'/'+value.longitude+'">'+value.latitude+', '+value.longitude+'</a></p>' +
                            '<p class="link"><a href="value.bird.url">Lien vers la fiche INPN</a></p> ' +
                            '<p class="imgMsg">L\'utilisateur n\'a pas pris de photo de l\'espèce observée.</p>' +
                            '<p class="description">'+$description+'</p> '
                        );
                    }
                    $('#results').append('<form method="post" action="/admin/validate/observations/confirm/'+value.id+'">' +
                        '<input type="submit" class="btn btn-primary accept" value="Valider et Publier"> ' +
                        '</form>' +
                        '<form method="post" action="/admin/validate/observations/refuse/'+value.id+'">' +
                        '<input type="submit" class="btn btn-danger refuse" value="Supprimer" ' +
                        '</form> ');
                })
            }).fail(function(jqXHR, exception){
                var msg = '';
                if (jqXHR.status === 404) {
                    msg = 'Espèce non présente dans le référentiel TAXREF de l\'INPN.';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (jqXHR.status === 422) {
                    msg = 'Cette espèce n\'a pas d\'observation en attente de validation';
                } else {
                    msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                }
                $('#errorMsg').remove();
                $('#clear').after('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
            })
        };
        submit();
    });
});