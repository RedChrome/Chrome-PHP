<fieldset>
    <legend>Registrieren:</legend>
    <?php echo $FORM->render('error'); ?>
    <?php echo $FORM->render('Register_StepTwo'); ?>
        <table border="0" cellpadding="0" cellspacing="10">
            <tr>
                <td><?php echo $LANG->get('email');?></td>
                <td><?php echo $FORM->render('email'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo $LANG->get('password'); ?></td>
                <td><?php echo $FORM->render('password'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo $LANG->get('password_again'); ?></td>
                <td><?php echo $FORM->render('password2'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo $LANG->get('nickname'); ?></td>
                <td><?php echo $FORM->render('nickname'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Geburtstag:</td>
                <td><?php echo $FORM->render('birthday'); ?></td>
                <td></td>
            </tr>
            <tr>
                <td>Captcha:</td>
                <td><?php echo $FORM->render('captcha'); ?></td>
                <td><?php if($FORM->hasValidationErrors('captcha')) {
                    echo $LANG->get('captcha_wrong');
                }  ?></td>
            </tr>
            <tr>
                <td><?php echo $FORM->render('buttons')->element('backward'); ?></td>
                <td colspan="2"><?php echo $FORM->render('buttons')->element('submit'); ?></td>
                <td></td>
            </tr>
        </table>
    <?php echo $FORM->render('Register_StepTwo'); ?>
</fieldset>