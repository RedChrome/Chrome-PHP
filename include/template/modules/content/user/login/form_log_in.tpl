<?php
$FORM->getDecorator('identity')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, $LANG->get('email_address'));

$FORM->getDecorator('password')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, $LANG->get('password'));
$FORM->getDecorator('stay_loggedin')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, array($LANG->get('stay_loggedin')));
?>


<?php echo $FORM->render('login'); ?>
<h6><?php echo $LANG->get('login'); ?></h6>

        <?php echo $FORM->render('identity'); ?>

        <?php echo $FORM->render('password');?>

            <?php echo $FORM->render('stay_loggedin'); ?>

<?php echo $FORM->render('submit'); ?>


<?php echo $FORM->render('login'); ?>
