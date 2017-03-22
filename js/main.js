$(document).ready(function(){
    $("#submit-btn").click(function() {
    	$('#spinner').css('visibility', 'visible');
    	$('#submit-btn').css('visibility', 'hidden');
    	$('#submit-btn').prop('disabled', true);
        $.post('/info/set_urls', {urls: $('#url-list').val()}, function(result){
        	// console.log(result);
        	var res = JSON.parse(result);
        	// console.log(res);
        	res.forEach(function(item){
        		$('#results')
        			.after('<tr scope="row"><td>' + item.url + '</td><td>' + item.title + '</td><td>' + item.status +  '</td></tr>');
        	});
    		$('#spinner').css('visibility', 'hidden');
    		$('#submit-btn').prop('disabled', false);
    		$('#submit-btn').css('visibility', 'visible');
        });
    });
});