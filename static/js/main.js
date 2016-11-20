$("#twitter-login").click(function(e){
	e.preventDefault();
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
		console.log(data);
		var size = Object.keys(data).length;
		console.log(" Nombre de résultats:  "+size);
		if (size > 0)
		{
			console.log("Rien trouvé... :/ ");
			$.each(data, function(idx){
				result = data[idx];
				console.log("Trouvé : "+result["img"]);
				$content = $("<div class=\"grid-item\"><a href=\"/create.php?query="+encodeURIComponent(result["img"])+"\"><img src=\""+result["thumb"]+"\" /></a></div>");
				$("#results")
					.append($content)
					.masonry( 'appended', $content );
			});
		}

		else
				{
					

					/*$content = $("<div class=\"grid-item\" ><img style =\"width: 520px\" src=\"static/images/pageVide.gif\" /></div>");
					//console.log("Je crée : "+$content);
					$("#results")
						.append($content)
						.masonry( 'appended', $content );*/

					$content = $("<div class=\"grid-item\" style=\"text-align:center;font-size:28px;\"> Pas de résultat à votre recherche... </div>");
					$("#results")
						.append($content)
						.masonry( 'appended', $content );

					$.get("/api/fetch.php", { "query" : "vide" },function(data){
						$.each(data, function(idx){
							result = data[idx];
							//console.log("Trouvé : "+result["img"]);
							$content = $("<div class=\"grid-item\"><a href=\"/create.php?query="+encodeURIComponent(result["img"])+"\"><img src=\""+result["thumb"]+"\" /></a></div>");
							$("#results")
						.append($content)
						.masonry( 'appended', $content );
					});
				});
			}

		$("#results").imagesLoaded().progress(function(){
			$("#results").masonry('layout');
		});
	});

	event.preventDefault();
});

$("#search").submit();

});