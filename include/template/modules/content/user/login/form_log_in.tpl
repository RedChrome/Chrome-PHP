<?php
$FORM->getElement('identity')->getDecorator()
        ->setAttribute('size', 25);

$FORM->getElement('password')->getDecorator()
        ->setAttribute('size', 25);
?>

<fieldset><legend><?php echo $LANG->get('login'); ?></legend>
<?php echo $FORM->render('login'); ?>

<table align="center">
    <tr>
        <td align="left"><?php echo $LANG->get('email_address'); ?>:</td>
        <td><?php echo $FORM->render('identity'); ?></td>
    </tr>
    <tr>
        <td align="left"><?php echo $LANG->get('password'); ?>:</td>
        <td><?php echo $FORM->render('password');?></td>
    </tr>
    <tr>
        <td colspan="2">
            <?php echo $FORM->render('stay_loggedin'); ?><?php echo $LANG->get('stay_loggedin'); ?>
            <?php echo $FORM->render('submit'); ?>
        </td>
    </tr>
</table>

<?php echo $FORM->render('login'); ?>
</fieldset>