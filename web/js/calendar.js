function getClient() {
    $.ajax({
        url: Routing.generate('application_get_client_evenement'),
        beforeSend: function () {
        },
        success: function (data) {
            console.log("client chargé")
        }
    });
}

(function () {
    if(document.getElementById('iframe')){
        div = $('#iframe');
        var iframe = document.querySelector('#iframe iframe');
        iframe.width = div.width();
    }
})();