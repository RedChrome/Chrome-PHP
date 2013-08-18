<?php
    $FORM['Register_StepOne']->setAttribute('class', 'ym-form ym-columnar ym-linearize');
    echo $FORM['Register_StepOne']->render();
?>
<?php //echo $FORM->render('error'); ?>
<h6 class="ym-fbox-heading"><?php echo $LANG->get('register_continue'); ?></h6>
<div class="ym-fbox-text">
    <label for="rules"><?php echo $LANG->get('our_rules'); ?></label>
    <textarea id="rules" rows="25" readonly="readonly"><?php echo $LANG->get('registration_rules'); ?></textarea>
</div>
<div class="ym-fbox ym-fbox-check">
    <?php echo $FORM['accept']->render(); ?>
</div>
<br />
<div class="ym-fbox-button">
    <?php echo $FORM['submit']->render(); ?>
</div>
<?php echo $FORM['Register_StepOne']->render(); ?>