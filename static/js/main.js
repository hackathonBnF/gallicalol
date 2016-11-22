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

function next(){
	var current_page = parseInt($("#page").html());
	var records = parseInt($("#total").html());

	// console.log(current_page);

	if( current_page * 50 <= records ){
		current_page += 1;
		$("#page").html(current_page);

		$("#loading").show();

		var query = {
			"query" : $("#query").val(),
			"page": current_page
		};

		$.get("/api/fetch.php", query, function(data){
			$("#loading").hide();
			results_append(data["results"], $("#results"));

			if ( parseInt($("#page").html()) <  Math.ceil($("#total").html()/50) ) $("#pagination").show();

		});
	}
}

$("#next a").click(function(e){
		e.preventDefault();
		$("#pagination").hide();
		next();
});

function results_append(results, $target){

	$.each(results, function(idx){
		result = results[idx];

		$content = $("<div class=\"grid-item\"><a href=\"/create.php?query="+encodeURIComponent(result["img"])+"\"><img src=\""+result["thumb"]+"\" /></a></div>");

		$target
			.append($content)
			.masonry('appended', $content);
	});

	$target.imagesLoaded().progress(function(){
		$target.masonry('layout');
	});
}

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
	$("#page").html("1");
	$("#total").html("0");

	$("#loading").show();
	$("#messages").hide();
	$("#results").empty();

	$("#results").masonry('layout');

	e.preventDefault();

	$.get("/api/fetch.php", { "query" : $("#query").val() },function(data){
		$("#loading").hide();

		//console.log(data);
		//console.log(data[0]["img"]);

		if (data["results"].length == 0) $("#messages").show();

		results_append(data["results"], $("#results"));

		$("#total").html(data['records']);

		if ( parseInt($("#page").html()) <=  Math.ceil($("#total").html()/50) ) $("#pagination").show();

		/* ça marche à moitié
		console.log(Math.ceil($("#total").html()/50));

		$("#results").infinitescroll({
			debug: true,
			// loading : {
			//	start: function(){ $("#loading").show(); },
			//	finished: function(){ $("#loading").hide(); }
			// },
			navSelector: "nav#pagination",
			nextSelector: "#next a",
			itemSelect: ".grid-item",
			path: function(page){
				var query = {
					"query" : $("#query").val(),
					"page": page
				};

				return "/api/fetch.php?"+$.param(query);
			},
			dataType: "json",
			maxPage: Math.ceil($("#total").html()/50),
			appendCallback: false
		}, function(data, opts){
			results_append(data["results"], $("#results"));
		});
		*/

	});
});

$("#search").submit();

});
