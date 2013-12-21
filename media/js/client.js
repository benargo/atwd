$(function(){
	$('#index a').click(function(event){
		event.preventDefault();
		$('#application').delay(3000).fadeIn(1000);
		$('#index').addClass('columns', 0).addClass('four', {
			duration: 3000,
			queue: false
		}).addClass('offset-by-twelve', {
			duration: 3000,
			queue: false,
			complete: function()
			{
				$('#index').removeClass('offset-by-twelve', 0);
			}
		});
	});
});