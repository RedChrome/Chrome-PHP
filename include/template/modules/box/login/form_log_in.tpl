<?php
	$FORM['login']->getAttribute()->setAttribute('class', 'ym-form ym-columnar');
	$FORM['login']->getAttribute()->setAttribute('id', 'login');
	echo $FORM['login']->render();
?>
<div class="ym-fbox ym-fbox-text">
<?php echo $FORM['identity']->render(); ?>
</div>

<div class="ym-fbox ym-fbox-text">
<?php echo $FORM['password']->render(); ?>
</div>
<br />
<div class="ym-fbox ym-fbox-check">
<?php echo $FORM['stay_loggedin']->render(); ?>
</div>

<div class="ym-fbox ym-fbox-button ym-fbox-footer">
<?php
	echo $FORM['submit']->render();
?>
</div>
<img id="login_ajax_waiting" src="public/image/ajax/Bert.gif" class="invisible" alt="waiting"/>
<?php
	echo $FORM['login']->render();
?>
