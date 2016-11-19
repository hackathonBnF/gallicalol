$(document).ready(function(){

var cb = new Codebird;
cb.setConsumerKey("tDBS0qgbxYPWBiSSKJNHV8rkY", "kCMBpJNEMWAm8ndOZ0hBHnnGKBz1rRuQ0AS6OcAQlu6Z6cHVgR");

$("#twitter-login").click(function(e){
	//var cb          = new Codebird;
	var current_url = document.URL;
	var query       = current_url.match(/\?(.+)$/).split("&amp;");
	var parameters  = {};
	var parameter;

	//cb.setConsumerKey("STUFF", "HERE");

	for (var i = 0; i < query.length; i++) {
	    parameter = query[i].split("=");
	    if (parameter.length === 1) {
	        parameter[1] = "";
	    }
	    parameters[decodeURIComponent(parameter[0])] = decodeURIComponent(parameter[1]);
	}

	// check if oauth_verifier is set
	if (typeof parameters.oauth_verifier !== "undefined") {
	    // assign stored request token parameters to codebird here
	    // ...
	    cb.setToken(stored_somewhere.oauth_token, stored_somewhere.oauth_token_secret);

	    cb.__call(
	        "oauth_accessToken",
	        {
	            oauth_verifier: parameters.oauth_verifier
	        },
	        function (reply) {
	            cb.setToken(reply.oauth_token, reply.oauth_token_secret);

	            // if you need to persist the login after page reload,
	            // consider storing the token in a cookie or HTML5 local storage
	        }
	    );
	}
});
});