// autocomplet : this function will be executed every time we change the text
function autocomplete() {
    var pathname = window.location.pathname;//  /projet-dev/comptes/fr
    var val = pathname.split('/')[2];
    var url_cible;
    if(val=='comptes'){
        url_cible = window.location.href.replace('comptes/fr', 'ajax_refresh_compte.php')
    }else if(val=='compteadmin'){
        url_cible = window.location.href.replace('compteadmin/fr', 'ajax_refresh_compte.php')
    }
    var keyword = $('#nom').val();
    $.ajax({
        url: url_cible,
        type: 'POST',
        data: {keyword: keyword},
        success: function (data) {
            $('#nom_list_id').show();
            $('#nom_list_id').html(data);
        }
    });
}
// set_item : this function will be executed when we select an item
function set_item(item) {
    $('#nom').val(item);
    $('#nom_list_id').hide();
}
