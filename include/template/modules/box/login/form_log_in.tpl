<?php echo $FORM->render('login'); ?>
<?php echo $FORM->render('credential'); ?>

<?php echo $FORM->render('password'); ?>

<br /><br />
<?php echo $LANG->get('stay_loggedin');?> <?php echo $FORM->render('stay_loggedin'); ?>

<?php
$FORM->getElement('submit')->getDecorator()->setAttribute('onclick', 'javascript:AJAX_send_login();return false');

echo $FORM->render('submit');
?>

<img name="login_ajax_waiting" src="<?php echo IMAGE; ?>ajax/Bert.gif" class="invisible"/>

<?php echo $FORM->render('login'); ?>