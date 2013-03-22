<?php echo $FORM->render('Register_StepOne'); ?>

<?php echo $FORM->render('error'); ?>

<h6 class="ym-fbox-heading"><?php echo $LANG->get('register_continue'); ?></h6>
<div class="ym-fbox-text">
    <label for="rules"><?php echo $LANG->get('our_rules'); ?></label>
    <textarea id="rules" rows="25" readonly="readonly"><?php echo $LANG->get('registration_rules'); ?></textarea>
</div>

    <?php echo $FORM->render('accept'); ?>

<br />

<div class="ym-fbox-button">
    <?php echo $FORM->render('submit'); ?>
</div>

<?php echo $FORM->render('Register_StepOne'); ?>