
<br />
<?php echo $FORM->render('Index'); ?>

    <input type="text" name="text" value="<?php echo $TOKEN;?>" size="35"/>
    <br />Password <?php echo $FORM->render('password'); ?>
    <br /><br />
    <?php echo $FORM->render('radio'); ?>Test<br />
    <?php echo $FORM->render('radio'); ?>Test2<br />


    <br />
    <?php echo $FORM->render('checkbox'); ?> Value 1<br />
    <?php echo $FORM->render('checkbox'); ?> Value 2<br />
    <?php echo $FORM->render('checkbox'); ?> Value 3<br />


    <?php echo $FORM->render('select'); ?>

    <br />
    <br />
    <?php echo $FORM->render('submit'); ?>

<?php echo $FORM->render('Index'); ?>