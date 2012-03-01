document.writeln('<div class="dojo_main" id="dojo_main"></div>');dojo.require("dojo.fx");var ani_id=0;var ani_start=0;var Animations=new Array();function message_show(text,delay){++ani_id;if(delay<5000||!delay)delay=5000;dojo.byId('dojo_main').innerHTML=dojo.byId('dojo_main').innerHTML+'<div class="dojo_box" id="id_'+ani_id+'">'+text+'<br><small><a href="#" onclick="message_hide(\''+ani_id+'\')">schlie&szlig;en</a></small></div>';dojo.fx.combine([dojo.fx.slideTo({node:"id_"+ani_id,duration:2500,left:0,top:0}),dojo.fadeIn({node:"id_"+ani_id,duration:1500}),dojo.fadeOut({easing:function(){message_hide(ani_id)},delay:delay,node:"id_"+ani_id,duration:50})]).play();}function message_hide(id){dojo.fadeOut({node:"id_"+id,duration:50}).play();dojo.fx.slideTo({node:"id_"+id,duration:2500,left:20,top:document.body.offsetHeight}).play();dojo.byId('id_'+id).innerHTML="";}var wipens=new Array();function wipe(t,n,d){if(wipens[n]==undefined){if(t=='wipeOut'){var anim=dojo.fx.wipeOut({node:n,duration:d}).play();wipens[n]='wipeIn';}else{var anim=dojo.fx.wipeIn({node:n,duration:d}).play();wipens[n]='wipeOut';}}else{if(wipens[n]=='wipeOut'){var anim=dojo.fx.wipeOut({node:n,duration:d}).play();wipens[n]='wipeIn';}else{var anim=dojo.fx.wipeIn({node:n,duration:d}).play();wipens[n]='wipeOut';}}}


function AJAX_waiting(form) {
	dojo.query('#' + form + '> img[id='+form+'_ajax_waiting]').removeClass('invisible');
	dojo.query('#' + form + '> img[name='+form+'_ajax_waiting]').removeClass('invisible');
}

function AJAX_unwaiting(form) {
	dojo.query('#' + form + '> img[id='+form+'_ajax_waiting]').addClass('invisible');
	dojo.query('#' + form + '> img[name='+form+'_ajax_waiting]').addClass('invisible');
}
