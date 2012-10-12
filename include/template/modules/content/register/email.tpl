Hallo <?php echo $name; ?>,<br />
<br />
Sie haben sich erfolgreich auf <?php echo Chrome_Config::getConfig('Site', 'name'); ?> registriert.<br />
Um die Registrierung abzuschlieﬂen m&uuml;ssen sie folgende Seite besuchen:<br /><br />

<a href="/registrierung_bestaetigen.html?activationKey=<?php echo $activationKey; ?>">Link</a><br /><br />

Falls sie nicht weitergeleitet werden, besuchen Sie die Seite manuell und geben folgenden aktivierungs Schl&uuml;ssel ein:<br /><br />

<?php echo $activationKey; ?><br /><br />

Viele Gr&uuml;ﬂe,<br />
Ihr <?php echo Chrome_Config::getConfig('Site', 'name'); ?> Team