

<?php echo $FORM->render('Captcha_Test'); ?>
<?php echo $FORM->render('error'); ?>
<h6 class="ym-fbox-heading">Captcha Test:</h6>



    <div class="ym-fbox-text">
        <label for="captcha">Captcha: </label>
        <?php echo $FORM->render('captcha'); ?>
    </div>

    <?php echo $FORM->render('buttons')->element('submit'); ?>


<?php echo $FORM->render('Captcha_Test'); ?>