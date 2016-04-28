require(["dojo/cookie"], function(cookie){
		var timezone = cookie("CHROME_TIMEZONE");
		if(timezone == undefined) {
			cookie("CHROME_TIMEZONE", jstz.determine().name(), {expires: 365}); 
		}
});