$("#twitter-login").click(function(e){
	e.preventDefault();
	var $target = $(e.currentTarget);

	var isAuthenticated = $target.data('is-authenticated');

	if (!isAuthenticated) {
		$.oauthpopup({
	        path: '/twitter/login.php',
	        callback: function () {
	        	$('#twitter-modal').modal('show');
	        }
	    });
	} else {
		$('#twitter-modal').modal('show');
	}
});

// Update image when modal is shown
$('#twitter-modal').on('shown.bs.modal', function () {
	$('#twitter-modal-img')
		.hide()
		.attr('src', document.getElementById('canvas').toDataURL('image/jpeg'))
		.show();
});

$('#post-to-twitter').on('click', function(e) {
	e.preventDefault();

	var data = {
		status: $('#tweet-text').val(),
    	image: document.getElementById('canvas').toDataURL('image/jpeg')
    };

    $.post('/twitter/media_upload.php', data).done(function(data) {
    	$('#twitter-modal').modal('hide');
    });

})

$(document).ready(function(){

var params={};
window.location.search
  .replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
    params[key] = value;
  }
);

//console.log("query", params["query"]);

if(params["query"] != "" && params["query"] != undefined){

	$("#image-url").val(decodeURIComponent(params["query"]));
	$('#download-into-canvas').click();
	$("#media").hide();
}

if ($('#results').length === 1) {
	$("#results").masonry({ itemSelector: '.grid-item', columnWidth: 285 });
}

$("#search").submit(function(e){
	$("#loading").show();
	$("#messages").hide();
	$("#results").empty();

	$("#results").masonry('layout');

	e.preventDefault();

	// $grid.imagesLoaded().progress( function() {
	// 	$grid.masonry('layout');
	// });


	$.get("/api/fetch.php", { "query" : $("#query").val() },function(data){

		$("#loading").hide();

		//console.log(data);
		//console.log(data[0]["img"]);

		if (data.length == 0) $("#messages").show();

		$.each(data, function(idx){
			result = data[idx];
			//console.log(result["img"]);

			$content = $("<div class=\"grid-item\"><a href=\"/create.php?query="+encodeURIComponent(result["img"])+"\"><img src=\""+result["thumb"]+"\" /></a></div>");

			$("#results")
				.append($content)
				.masonry( 'appended', $content );
		});


		$("#results").imagesLoaded().progress(function(){
			$("#results").masonry('layout');
		});
	});
});

$("#search").submit();

});
