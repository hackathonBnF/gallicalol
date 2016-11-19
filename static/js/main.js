$(document).ready(function(){

$("#search").submit(function(e){
	$("#results").empty();

	// $grid.imagesLoaded().progress( function() {
	// 	$grid.masonry('layout');
	// });


	$.get("/api/fetch.php", { "query" : $("#query").val() },function(data){
		//console.log(data);
		console.log(data[0]["img"]);

		$.each(data, function(idx){
			result = data[idx];
			console.log(result["img"]);
			$("#results").append($("<div class=\"grid-item\"><img src=\""+result["thumb"]+"\" /></div>"));
		});

		$("#results").masonry({ itemSelector: '.grid-item', columnWidth: 220 });
		
		$("#results").imagesLoaded().progress(function(){
			$("#results").masonry('layout');
		});
	});

	event.preventDefault();
});





$("#search").submit();

});