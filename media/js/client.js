// Colors picked with thanks to Adobe Kuler
// https://kuler.adobe.com/Chart-Colors-1-color-theme-3358355/
var chartColors = ['#6CD5FF', '#6EE862', '#FFE078', '#E87662', '#BB78FF', '#514DFF', '#46E8BA', '#E2FF59', '#E89F46', '#FF59B8'];

$(function(){
	$('#get-region select[name="select-region"]').change(function() {
		var time_period = $('#get-region select[name="time-period"]').val();
		var region_name = $(this).val().replace(/ /g,'_').toLowerCase();
		$.ajax({
			type: 'GET',
			url: 'http://www.cems.uwe.ac.uk/~b2-argo/atwd/crimes/'+ time_period + '/'+ region_name + '/json',
			crossDomain: true, // with help from http://enable-cors.org/server_php.html
			ifModified: true,

		}).done(function(data, textStatus, jqXHR) {
    		if(jqXHR.status == 200)
    		{
    			// Override local storage if the data has changed
				window.localStorage.setItem('cache_'+ region_name, JSON.stringify(data));
			}


			// Create two new variables for holding the data that we'll pass to the chart library
			var barData = {
				labels: ['Areas'],
				datasets: [/*{
					fillColor: chartColors[0],
					strokeColor: chartColors[0],
					data: []
				}*/]
			};
			var pieData = [];

			$.each(data.response.crimes.region.area, function(key, area) {
			/*	barData.labels.push(area.id);
				barData.datasets[0].data.push(area.total); */
				barData.datasets.push({
					fillColor: chartColors[key],
					strokeColor: chartColors[key],
					data: [area.total]
				})
				pieData.push({
					value: area.total,
					color: chartColors[key]
				});

				$('section.chart.key ul').append('<li class="key item '+ chartColors[key] +'">'+ area.id +'</li>');
			});
			
			// Produce the bar chart
			var ctx = document.getElementById('bar').getContext('2d');
			var barChart = new Chart(ctx).Bar(barData, {barValueSpacing: 10});

			// Produce the pie chart
			var ctx = document.getElementById('pie').getContext('2d');
			var pieChart = new Chart(ctx).Pie(pieData, {});

			// Show the charts
			$('section.chart').show();
		});
	});
});