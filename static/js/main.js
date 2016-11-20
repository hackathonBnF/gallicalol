$("#twitter-login").click(function(e){
	console.log("open");
	window.open("/twitter/login.php", "_temp",'resizable=yes,width=600,height=600');
});

$(document).ready(function(){

var params={};
window.location.search
  .replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
    params[key] = value;
  }
);

console.log("query", params["query"]);

if(params["query"] != "" && params["query"] != undefined){

	$("#image-url").val(decodeURIComponent(params["query"]));
	$('#download-into-canvas').click();
	$("#media").hide();
}

var twitter_login = setInterval(function(){
	$.get("/twitter/check.php", function(data){
		if(!data["login"]){
			$("#twitter-post").hide();
		} else {
			$("#twitter-login").hide();
			$("#twitter-post").show();

			clearTimeout(twitter_login);
		}
	});
}, 500);
//clearTimeout(twitter_login);

$("#results").masonry({ itemSelector: '.grid-item', columnWidth: 220 });

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

			$content = $("<div class=\"grid-item\"><a href=\"/create.php?query="+encodeURIComponent(result["img"])+"\"><img src=\""+result["thumb"]+"\" /></a></div>");

			$("#results")
				.append($content)
				.masonry( 'appended', $content );
		});

		
		$("#results").imagesLoaded().progress(function(){
			$("#results").masonry('layout');
		});
	});

	event.preventDefault();
});

$("#search").submit();

});