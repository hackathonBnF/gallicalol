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
		$target
			.append('<div class="grid-sizer"></div>')

	$.each(results, function(idx){
		result = results[idx];

		$content = $('<div class="grid-item grid-item--width2"><a href="/'+result["id"]+'.meme"><img src="'+result["thumb"]+"\" /></a></div>");

		$target
			.append($content)
			.masonry('appended', $content);
	});

	$target.imagesLoaded().progress(function(){
		$target.masonry('layout');
	});
}

$(document).ready(function(){

if ($('#results').length === 1) {
	$("#results").masonry({ itemSelector: '.grid-item', columnWidth: '.grid-sizer', gutter: 6, percentPosition: true });
}

$("#search").submit(function(e){
	$("#page").html("1");
	$("#total").html("0");

	$("#loading").show();
	$("#messages").hide();
	$("#results").empty();

	$("#results").masonry('layout');

	e.preventDefault();

	// Update browser query string with search term
	var queryParamName = $("#query").attr('name');
	window.history.pushState({}, $('title').text(), '/?'+queryParamName+'='+$("#query").val());

	$.get("/api/fetch.php", { "query" : $("#query").val() },function(data){
		$("#loading").hide();

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
