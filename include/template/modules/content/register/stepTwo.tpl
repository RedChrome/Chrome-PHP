

<?php echo $FORM->render('Register_StepTwo'); ?>
<?php echo $FORM->render('error'); ?>
<h6 class="ym-fbox-heading">Registrieren:</h6>


        <?php echo $FORM->render('email'); ?>



        <?php echo $FORM->render('password'); ?>



        <?php echo $FORM->render('password2'); ?>



        <?php echo $FORM->render('nickname'); ?>



     <?php echo $FORM->render('birthday'); ?>




        <?php echo $FORM->render('captcha'); ?>


    <?php echo $FORM->render('buttons')->element('backward'); ?>
    <?php echo $FORM->render('buttons')->element('submit'); ?>


<?php echo $FORM->render('Register_StepTwo'); ?>