/**
 * Created by leo on 01/07/17.
 */
$(function(){

    //display map
    var mymap = L.map('mapid').setView([46.52863469527167, 2.43896484375], 5);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoibGVvZ3JhbWJlcnQiLCJhIjoiY2o0bHFvZWZsMTR2czMxbXo1OWlvamw5cSJ9.H0OEY_8Tu0Me5hgunS7CQw'
    }).addTo(mymap);

    var $familyField = $('select#familles');
    var $orderField = $('select#ordres');
    var $birdField = $('#birdField');

    //function to clear map
    $('#clearMap').click(function (e) {
        e.preventDefault();
        location.reload();
    });

    //function to sort families by order
     $orderField.on('change', function (e) {
         e.preventDefault();

         var submit = function(){
             var $orderFieldUrl = '/search/order/'+$orderField.val();
            //returns an ajax call
             return $.ajax({
                 url: $orderFieldUrl,
                 method: 'GET'
             }).done(function (response) {
                 //If it's a success, we clean family field before to add results in it
                 $('#familles').empty().append('<option></option>');
                 $('#birdField').val('');
                 $.each(response.families, function(key, value){
                     var $toAdd="<option value='"+value.famille+"'>"+value.famille+"</option>";
                     $('#familles').append($toAdd);
                 })
             }).fail(function(jqXHR, exception){
                 var msg = '';
                 if (jqXHR.status === 404) {
                     msg = 'Page not found 404';
                 } else if (jqXHR.status === 500) {
                     msg = 'Internal Server Error [500].';
                 } else {
                     msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                 }
                 $('#errorMsg').remove();
                 $('form').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
             })
         };
         submit();
     });

     //function which does exactly the same thing than previously, but for birds (sort by family)
    $familyField.on('change', function (e) {
        e.preventDefault();
        var submit = function(){
            var $familyFieldUrl = '/search/family/'+$familyField.val();
            return $.ajax({
                url: $familyFieldUrl,
                method: 'GET'
            }).done(function (response) {
                $('#birds').empty();
                $('#birdField').val('');
                var $nameBirds = [];
                $.each(response.birds, function(key, value){
                    if(value.nomVern !== ''){
                        if(jQuery.inArray(value.nomVern, $nameBirds) !== -1){
                            console.log('Cet oiseau est déjà dans la liste');
                        } else {
                            $nameBirds.push(value.nomVern);
                            $('#birds').append(
                                '<option class="birdOption" value="'+value.nomVern+'"  id="'+value.id+'">'
                            );
                            $('#'+value.id).append(
                                '<input id="getUrl-'+value.id+'" type="hidden" value="'+value.url+'"/>'
                            )
                        }
                    }
                });
            }).fail(function(jqXHR, exception){
                var msg = '';
                if (jqXHR.status === 404) {
                    msg = 'Page not found 404';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else {
                    msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                }
                $('#errorMsg').remove();
                $('form').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
            })
        };
        submit();
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
            var $birdFieldUrl = '/search/bird/accepted/'+$endval;
            return $.ajax({
                url: $birdFieldUrl,
                method: 'GET'
            }).done(function(response){
                $('#errorMsg').remove();
                $.each(response.observations, function(key, value){
                    var date = new Date(value.date.date);
                    date = (date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear());
                    if($('#isLogged').val() === "true"){
                        //Get user picture
                        var $gravatarUrl = "https://www.gravatar.com/avatar/"+md5(value.user.email)+"?default="+encodeURIComponent('https://leogrambert.fr/front/projets/blogEcrivain/blog/web/img/user.png');
                        //Get user roles
                        var $role = value.user.roles;
                        var $roleString = "";
                        if($.inArray("ROLE_ADMIN", $role)){
                            if ($.inArray("ROLE_PRO", $role)){
                                $roleString = 'Amateur';
                            } else {
                            $roleString = 'Professionnel';
                            }
                        } else{
                            $roleString = 'Administrateur';
                        }
                        //Display observation sheet
                        if(value.picture !== null) {
                            $('#colMap').append(
                                '<div class="col-md-5 col-xs-12 observationContainer">' +
                                    '<div class="row layer">' +
                                        '<div class="col-xs-4">' +
                                            '<img class="profilePicture" src="'+$gravatarUrl+'" alt="profilePicture"/> ' +
                                        '</div>' +
                                        '<div class="col-xs-8">' +
                                            '<p class="user">'+value.user.username+'</p>' +
                                            '<p class="role">'+$roleString+'</p>' +
                                        '</div>'+
                                    '</div>' +
                                    '<div class="row contain">' +
                                        '<div class="col-xs-6">' +
                                        '<p class="link"><a href="'+value.bird.url+'">Lien fiche INPN</a></p>' +
                                        '<a href="/uploads/img/'+value.picture.id+'.'+value.picture.ext+'" class="thumbnail" target="_blank" title="Ouvrir l\'image dans un nouvel onglet"><img class="imgObservation" src="/uploads/img/' + value.picture.id + '.' + value.picture.ext + '" alt="' + value.picture.alt + '" /></a>' +
                                        '</div>' +
                                        '<div class="col-xs-6">' +
                                            '<p class="nameBird">'+value.bird.nomVern +'<br><span class="date">le ' + date + '</span></p>' +
                                            '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                            '<p class="lon">Longitude : ' + value.longitude + '</p>'+
                                        '</div>' +
                                    '</div>' +
                                '</div>'
                               );
                        } else {
                            $('#colMap').append(
                                '<div class="col-md-5 col-xs-12 observationContainer">' +
                                    '<div class="row layer">' +
                                        '<div class="col-xs-4">' +
                                            '<img class="profilePicture" src="'+$gravatarUrl+'" alt="profilePicture"/> ' +
                                        '</div>' +
                                        '<div class="col-xs-8">' +
                                            '<p class="user">'+value.user.username+'</p>' +
                                            '<p class="role">'+$roleString+'</p>' +
                                        '</div>'+
                                    '</div>' +
                                    '<div class="row contain">' +
                                        '<div class="col-xs-6">' +
                                            '<p class="link"><a href="'+value.bird.url+'">Lien fiche INPN</a></p>' +
                                            '<img class="imgObservation" src="/bundles/core/img/logo.png" alt="no-picture" />' +
                                        '</div>' +
                                        '<div class="col-xs-6">' +
                                            '<p class="nameBird">'+value.bird.nomVern +'<br><span class="date">le ' + date + '</span></p>' +
                                            '<p class="lat">Latitude : ' + value.latitude + '</p>' +
                                            '<p class="lon">Longitude : ' + value.longitude + '</p>'+
                                        '</div>' +
                                    '</div>' +
                                '</div>'
                            );
                        }
                        //Add a marker on map
                        var marker = L.marker([value.latitude, value.longitude]).addTo(mymap);
                        marker.bindPopup("<b>"+value.bird.nomVern+" observé le "+date+" par "+value.user.username+"</b>");
                    } else {
                        var circle = L.circle([value.latitude, value.longitude], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 5000
                            }).addTo(mymap);
                    }
                })
            }).fail(function(jqXHR, exception){
                var msg = '';
                if (jqXHR.status === 404) {
                    msg = 'Espèce non présente dans le référentiel TAXREF de l\'INPN.';
                } else if (jqXHR.status === 500) {
                    msg = 'Internal Server Error [500].';
                } else if (jqXHR.status === 422) {
                    var $url = $('#getUrl-'+$endval).val();
                    msg = 'Cette espèce n\'a pas encore été observée. <a href="'+$url+'">Consultez sa fiche INPN</a>';
                } else {
                    msg = 'Une erreur s\'est produite. Veuillez réessayer.';
                }
                $('#errorMsg').remove();
                $('form').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
            })
        };
        submit();
    });

    //Get gps coordinates from controller to display marker for an untreated observation
    var $latGPS = $('.alert-success_lat').html();
    var $lonGPS = $('.alert-success_lon').html();
    if ($latGPS !== false && $lonGPS !== false){
        var marker = L.marker([$latGPS, $lonGPS]).addTo(mymap);
    }
});