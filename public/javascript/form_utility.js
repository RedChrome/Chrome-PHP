function truncate_form_input( formID ) {

    var form = dojo.byId(formID);

    elements =  form.getElementsByTagName("input");

    for(var i=0;i<elements.length;++i) {

        elements[i].required = false;

        if(elements[i].type == "submit") {
            continue;
        }
        elements[i].value = "";
        elements[i].required = "";
    }

    elements =  form.getElementsByTagName("select");

    // this will remove all select inputs
    for(var i=0;i<elements.length;++i) {

        if(elements[i].required == true) {
            elements[i].required = false;
            continue;
        }

	    selectParentNode = elements[i].parentNode;
    	newSelectObj = elements[i].cloneNode(false); // Make a shallow copy
	    selectParentNode.replaceChild(newSelectObj, elements[i]);
    }
}

function getToken()
{
	return Math.random();
}