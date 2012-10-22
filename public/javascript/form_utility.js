function truncate_form_input( formID ) {

    var form = dojo.byId(formID);

    elements =  form.getElementsByTagName("input");

    for(var i=0;i<elements.length;++i) {
        if(elements[i].type == "submit") {
            continue;
        }
        elements[i].value = "";
        elements[i].required = "";
    }

    elements =  form.getElementsByTagName("select");

    // this will remove all select inputs
    for(var i=0;i<elements.length;++i) {
	    selectParentNode = elements[i].parentNode;
    	newSelectObj = elements[i].cloneNode(false); // Make a shallow copy
	    selectParentNode.replaceChild(newSelectObj, elements[i]);
    }
}
