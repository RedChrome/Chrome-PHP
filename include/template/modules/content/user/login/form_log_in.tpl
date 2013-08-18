<?php
	$FORM['login']->setAttribute('class', 'ym-form linearize-form ym-columnar');
	echo $FORM['login']->render();
?>

<h6 class="ym-fbox-heading">Anmelden</h6>
<div class="ym-fbox ym-fbox-text">
<?php echo $FORM['identity']->render(); ?>
</div>

<div class="ym-fbox ym-fbox-text">
<?php echo $FORM['password']->render(); ?>
</div>

<div class="ym-fbox ym-fbox-check">
<?php echo $FORM['stay_loggedin']->render(); ?>
</div>
<div class="ym-fbox ym-fbox-button ym-fbox-footer">

<?php
	echo $FORM['submit']->render();
?>

</div>
<?php
	echo $FORM['login']->render();
?>

