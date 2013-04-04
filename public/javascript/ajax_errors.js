function error( response ) {


}

function warning( response ) {


}

function failure( response ) {
	message_show("Error from Server:" + response.statusText);
}

function exception( response, exception ) {
	message_show("Keine AJAX-Anfrage an " + response.url
    + " möglich: " + exception);
}

function handle( dataOrError, ioArgs)
{
	if(dojo.isString(dataOrError)){ // Response is a text, no error

        }else{	// Error Object
          	message_show("AJAX-Anfrage fehlgeschlagen!<br>Fehlermeldung:<br><font color=\"#FF0000\">" + dataOrError + "</font>");
        }
}