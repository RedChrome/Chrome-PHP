<fieldset class="fieldset">
    <legend><?php echo $LANG->get('our_rules'); ?></legend> 
    
    <?php echo $FORM->render('error'); ?>
    
    <?php echo $FORM->render('Register_StepOne'); ?>
    <p><?php echo $LANG->get('register_continue'); ?></p>
    
    <textarea cols="75" rows="20" readonly="readonly">
    <?php echo $LANG->get('registration_rules'); ?>
    </textarea>
    
    <br />
    <p>
        <label <?php if($FORM->hasValidationErrors('accept')) echo 'class="wrongInput"';?>>
            <?php echo $FORM->render('accept'); ?><strong><?php echo $LANG->get('rules_agree'); ?></strong>
        </label>
    </p>
    <br />
    
    <?php echo $FORM->render('submit'); ?>

    </form>
</fieldset>