// autocomplet : this function will be executed every time we change the text
function autocomplet() {
    var keyword = $('#nom').val();
    $.ajax({
        url: window.location.href.replace('param_projet/fr', 'ajax_refresh.php'),
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

function set_id(id) {
    $('#iduserCentraleAccueil').val(id);
}