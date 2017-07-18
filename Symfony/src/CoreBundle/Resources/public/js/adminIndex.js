/**
 * Created by leo on 18/07/17.
 */
$(function() {
    var $btnMoreField = $('#moreObservation');

    $btnMoreField.click(function (e) {
        e.preventDefault();
        //Get last observation id
        var $lastElementId = $btnMoreField.prev().attr('id').split('-');
        var $lastObservationId = $lastElementId[1];
        console.log($lastObservationId);
        var $moreObersationUrl = "/admin/more/"+$lastObservationId;
        return $.ajax({
            url: $moreObersationUrl,
            method: 'GET'
        }).done(function(response){

        }).fail(function(response){

        })
    })
});