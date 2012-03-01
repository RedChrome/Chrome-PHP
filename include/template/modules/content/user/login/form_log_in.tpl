<fieldset><legend><?php echo $LANG->get('login'); ?></legend>

<?php echo $FORM->render('login'); ?>

    <?php
    $FORM->getElement('credential')->getDecorator()
        ->setAttribute('size', 20)
        ->setAttribute('size', 30);

    echo $FORM->render('credential');


    ?><br /><br />
    <?php echo $FORM->render('password'); ?><br />

    <?php echo $FORM->render('stay_loggedin'); ?><?php echo $LANG->get('stay_loggedin'); ?>

	<input type="submit" name="submit" value="<?php echo $LANG->get('login'); ?>" /><br />

<?php echo $FORM->render('login'); ?>
</fieldset>