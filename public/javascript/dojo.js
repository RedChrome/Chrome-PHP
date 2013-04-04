document.writeln('<div class="dojo_main" id="dojo_main"></div>');
dojo.require("dojo.fx");
var ani_id = 0;
var ani_start = 0;
var Animations = new Array();

var TYPE_ERROR = 'error';
var TYPE_SUCCESS = 'success';
var TYPE_INFO = 'info';
var TYPE_WARNING = 'warning'

function message_show(text, type, delay) {
    ++ani_id;
    if (delay < 5000 || !delay) {
        delay = 5000;
    }

    var message_class = "dojo_box ";

    switch(type) {

        case TYPE_ERROR: {
             message_class += " error";
             break;
        }

        case TYPE_SUCCESS: {
            message_class += " success";
             break;
        }

        case TYPE_INFO: {
            message_class += " info";
            break;
        }

        case TYPE_WARNING: {
            message_class += " warning";
            break;
        }

        default: {
            message_class += type;
        }
    }

    dojo.byId('dojo_main').innerHTML = dojo.byId('dojo_main').innerHTML + '<div class="'+message_class+'" id="id_' + ani_id + '">' + text + '<br><small><a href="#" onclick="message_hide(\'' + ani_id + '\')">close</a></small></div>';
    dojo.fx.combine([dojo.fx.slideTo({
        node: "id_" + ani_id,
        duration: 1000,
        left: 0,
        top: 0
    }), dojo.fadeIn({
        node: "id_" + ani_id,
        duration: 500
    }), dojo.fadeOut({
        easing: function() {
            message_hide(ani_id)
        },
        delay: delay,
        node: "id_" + ani_id,
        duration: 3000
    })]).play();
}
function message_hide(id) {
    dojo.fadeOut({
        node: "id_" + id,
        duration: 500
    }).play();
    dojo.fx.slideTo({
        node: "id_" + id,
        duration: 1000,
        left: 0,
        top: window.innerHeight
    }).play();
    dojo.byId('id_' + id).innerHTML = "";
}
var wipens = new Array();

function wipe(t, n, d) {
    if (wipens[n] == undefined) {
        if (t == 'wipeOut') {
            var anim = dojo.fx.wipeOut({
                node: n,
                duration: d
            }).play();
            wipens[n] = 'wipeIn';
        } else {
            var anim = dojo.fx.wipeIn({
                node: n,
                duration: d
            }).play();
            wipens[n] = 'wipeOut';
        }
    } else {
        if (wipens[n] == 'wipeOut') {
            var anim = dojo.fx.wipeOut({
                node: n,
                duration: d
            }).play();
            wipens[n] = 'wipeIn';
        } else {
            var anim = dojo.fx.wipeIn({
                node: n,
                duration: d
            }).play();
            wipens[n] = 'wipeOut';
        }
    }
}


function AJAX_waiting(form) {
    dojo.query('#' + form + '> img[id=' + form + '_ajax_waiting]').removeClass('invisible');
    dojo.query('#' + form + '> img[name=' + form + '_ajax_waiting]').removeClass('invisible');
}

function AJAX_unwaiting(form) {
    dojo.query('#' + form + '> img[id=' + form + '_ajax_waiting]').addClass('invisible');
    dojo.query('#' + form + '> img[name=' + form + '_ajax_waiting]').addClass('invisible');
}