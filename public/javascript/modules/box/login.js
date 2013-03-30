$("#login").submit(function(evt) {
   evt.preventDefault();
   AJAX_request_login();
});

function AJAX_request_login() {

   AJAX_waiting("login");

   var form = $("#login");

   $.ajax({

      url: form.attr("action"),

      // add button value...
      data: form.serialize()+"&submit="+$("#login [type='submit']").attr("value"),

      type: form.attr("method"),

      dataType: "json",

      error: function(xhr, status) {
         message_show("Got an error while sending ajax request. Error message: " + status, TYPE_ERROR, 10000);
      },

      success: function(response) {
         // login failed
         if (response.success != true) {

            // renew the token
            if (typeof response.token != "undefined") {
               $('#login input[id="token"]')[0].value = response.token;
            }

            // setting css classes
            //dojo.query('#login > input[type=text]').addClass("wrongInput");
            //dojo.query('#login > input[type=password]').addClass("wrongInput");

            message_show(response.message, TYPE_WARNING, 5000);

         } else {

            message_show(response.message, TYPE_SUCCESS, 5000);

            // remove css class
            //dojo.query('#login > input').removeClass("wrongInput");

            // set reloadDelay, if not sent by server
            if (typeof response.reloadDelay == "undefined") {
               response.reloadDelay = 5000;
            }

            // if redirect url is not set, then reload current page
            if (typeof response.url != "undefined") {
               setTimeout("window.location.href = response.url", response.reloadDelay);
            } else {
               setTimeout("window.location.reload()", response.reloadDelay);
            }
         }
      },

      complete: function(xhr, status) {
         AJAX_unwaiting("login");
      }
   });

}