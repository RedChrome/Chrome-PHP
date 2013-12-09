<?php
	//$FORM['login']->getAttribute()->setAttribute('class', 'ym-form ym-columnar');
	//$FORM['login']->getAttribute()->setAttribute('id', 'login');
	echo $FORM['login']->render();
?>

<?php echo $FORM['identity']->render(); ?>


<?php echo $FORM['password']->render(); ?>

<br />

<?php echo $FORM['stay_loggedin']->render(); ?>



<?php
	echo $FORM['buttons']->render();
?>

<img id="login_ajax_waiting" src="public/image/ajax/Bert.gif" class="invisible" alt="waiting"/>
<?php
	echo $FORM['login']->render();
?>
