/**
 * Created by leo on 01/07/17.
 */
$(function(){

    var $familyField = $('select#familles');
    var $orderField = $('select#ordres');

    $('#clearForm').click(function (e) {
        e.preventDefault();
        location.reload();
    });

     $orderField.on('change', function (e) {
         e.preventDefault();
         var submit = function(){

             var $orderFieldUrl = '/search/order/'+$orderField.val();

             return $.ajax({
                 url: $orderFieldUrl,
                 method: 'GET'
             }).done(function (response) {
                 $('#familles').empty().append('<option></option>');
                 $('#birdField').val('');
                 $.each(response.families, function(key, value){
                     var $toAdd="<option value='"+value.famille+"'>"+value.famille+"</option>";
                     $('#familles').append($toAdd);
                 })
             }).error(function(){
                 console.log('erreur');
             })
         };
         submit();
     });

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
                $.each(response.birds, function(key, value){
                    var $toAdd="<option value='"+value.nomVern+"'>";
                   $('#birds').append($toAdd);
                })
            }).error(function(){
                console.log('erreur');
            })
        };
        submit();
    });

     var mymap = L.map('mapid').setView([46.52863469527167, 2.43896484375], 5);

     L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
         attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
         maxZoom: 18,
         id: 'mapbox.streets',
         accessToken: 'pk.eyJ1IjoibGVvZ3JhbWJlcnQiLCJhIjoiY2o0bHFvZWZsMTR2czMxbXo1OWlvamw5cSJ9.H0OEY_8Tu0Me5hgunS7CQw'
     }).addTo(mymap);

     var marker = L.marker([45.73, 4.89]).addTo(mymap);
     marker.bindPopup("<b>Fou d'Abbott observé le 30/06/2017 par LeoGrambert");
});