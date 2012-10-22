<?php
$FORM->getDecorator('stay_loggedin')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, array($LANG->get('stay_loggedin')));
?>

<?php echo $FORM->render('login'); ?>
<?php echo $FORM->render('identity'); ?>

<?php echo $FORM->render('password'); ?>

<br /><br />
<?php echo $FORM->render('stay_loggedin'); ?>

<?php
$FORM->getElement('submit')->getDecorator()->setAttribute('onclick', 'javascript:AJAX_send_login();return false');

echo $FORM->render('submit');
// we have to unset all additional attributes, cause this is a global form!
$FORM->getElement('submit')->getDecorator()->setAttribute('onclick', null);

?>

<img name="login_ajax_waiting" src="<?php echo IMAGE; ?>ajax/Bert.gif" class="invisible"/>

<?php echo $FORM->render('login'); ?>