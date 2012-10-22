<?php
$FORM->getDecorator('password')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, $LANG->get('password'));
$FORM->getDecorator('radio')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, array('Test', 'Test2'));
$FORM->getDecorator('checkbox')->setOption(Chrome_Form_Decorator_Abstract::CHROME_FORM_DECORATOR_LABEL, array('value1', 'VAlue2', 'Value3'));

?>
<br />
<?php echo $FORM->render('Index'); ?>

    <input type="text" name="text" value="<?php echo $TOKEN;?>" size="35"/>
    <?php echo $FORM->render('password'); ?>
    <br /><br />
    <?php echo $FORM->render('radio'); ?>
    <?php echo $FORM->render('radio'); ?>


    <br />
    <?php echo $FORM->render('checkbox'); ?><br />
    <?php echo $FORM->render('checkbox'); ?><br />
    <?php echo $FORM->render('checkbox'); ?>


    <?php echo $FORM->render('select'); ?>

    <br />
    <br />
    <?php echo $FORM->render('submit'); ?>

<?php echo $FORM->render('Index'); ?>