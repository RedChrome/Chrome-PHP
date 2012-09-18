function truncate_password_input( formID ) {

    var form = dojo.byId(formID);

    elements =  form.getElementsByTagName("input");

    for(var i=0;i<elements.length;++i) {

        if(elements[i].type == "password") {
            elements[i].value = "";
        }
    }
}
