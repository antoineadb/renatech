
// autocomplet : this function will be executed every time we change the text
function autocomplet() {
	var keyword = $('#nom').val();
	$.ajax({
		url: 'https://www.renatech.org/projet-dev/ajax_refresh.php',
		type: 'POST',
		data: {keyword:keyword},
		success:function(data){
			$('#nom_list_id').show();
			$('#nom_list_id').html(data);
		}
	});
}

// set_item : this function will be executed when we select an item
function set_item(item) {
	// change input value
	$('#nom').val(item);
	// hide proposition list
	$('#nom_list_id').hide();
}