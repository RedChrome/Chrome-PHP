<?php echo $FORM->render('login'); ?>
    Nickname: <input type="text" name="nickname" size="15" /><br />
    Password: <input type="password" name="password" value="" size="15" /><br />
    <input type="hidden" name="login" value="<?php echo $TOKEN;?>" />
    <br />
    Eingeloggt bleiben: <input type="checkbox" name="stay_loggedin" value="true" />
    <br />
    <input type="submit" name="submit" value="Login" />
<?php echo $FORM->render('login'); ?>