<?php
    echo $FORM['Register_StepOne']->render();
?><?php //echo $FORM->render('error'); ?>
<h6 class="ym-fbox-heading"><?php echo $LANG->get('register_continue'); ?></h6>
<div class="ym-fbox ym-fbox-text">
    <label for="rules"><?php echo $LANG->get('our_rules'); ?></label>
    <textarea id="rules" rows="25" readonly="readonly"><?php echo $LANG->get('modules/content/user/register/rules'); ?></textarea>
</div>
	<?php echo $FORM['accept']->render(); ?>
<br />
    <?php echo $FORM['buttons']->render(); ?>
<?php echo $FORM['Register_StepOne']->render(); ?>