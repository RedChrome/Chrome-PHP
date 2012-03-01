function AJAX_send_login() {

    AJAX_waiting('login');

    dojo.xhrPost({
        form: "login",
        url: dojo.byId('login').action,
        handleAs: "json",
        timeout: 3000,
        content: {
            request: "ajax"
            ,submit: dojo.byId('login').submit.value

        },
        load: function(data, ioArgs) {

            AJAX_unwaiting('login');

            // login failed
            if(data.success != true) {

                // renew the token
                if(typeof data.token != "undefined") {
                    dojo.query('#login > input[name=token]')[0].value = data.token;
                }

                // setting css classes
                dojo.query('#login > input[type=text]').addClass("wrongInput");
                dojo.query('#login > input[type=password]').addClass("wrongInput");

                message_show(data.message, 5000);

            } else {

                message_show(data.message, 5000);

                // remove css class
                dojo.query('#login > input').removeClass("wrongInput");

                // set reloadDelay, if not sent by server
                if(typeof data.reloadDelay == "undefined") {
                    data.reloadDelay = 5000;
                }

                // if redirect url is not set, then reload current page
                if(typeof data.url != "undefined") {
                    setTimeout("window.location.href = data.url", data.reloadDelay);
                } else {
                   setTimeout("window.location.reload()", data.reloadDelay);
                }
            }

        },
        error: function(err, ioArgs) {
            AJAX_unwaiting('login');
            message_show(err, 5000);
            alert(ioArgs);
        }
    });
}