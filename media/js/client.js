$(function(){
	$('#get-region select[name="select-region"]').change(function() {
		var time_period = $('#get-region select[name="time-period"]').val();
		var region_name = $(this).val().replace(/ /g,'_').toLowerCase();
		$.ajax({
			url: 'http://www.cems.uwe.ac.uk/~b2-argo/atwd/crimes/'+ time_period + '/'+ region_name + '/json',
			crossDomain: true, // with help from http://enable-cors.org/server_php.html
			ifModified: true
		}).done(function(data, textStatus, jqXHR) {
    		if(jqXHR.status == 200)
    		{
				window.localStorage.setItem('cache_'+ region_name, JSON.stringify(data));
			}
			
			$.each(data.response.crimes.region.area, function() {
				
			});
			//$.jqplot('bar')
		});
	});
});