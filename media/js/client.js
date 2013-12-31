$(function(){
	$('#get-region select[name="select-region"]').change(function() {
		var time_period = $('#get-region select[name="time-period"]').val();
		var region_name = $(this).val().replace(/ /g,'_').toLowerCase();
		$.ajax({
			url: 'http://www.cems.uwe.ac.uk/~b2-argo/atwd/crimes/'+ time_period + '/'+ region_name + '/json',
			crossDomain: true
		}).done(function(data) {
			alert('hi');
			console.log(data);
			window.localStorage.setItem('cache_'+ region_name, JSON.stringify(data));
			//$.jqplot('bar')
		});
	});
});