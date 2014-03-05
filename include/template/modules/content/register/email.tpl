Hallo <?php echo $name; ?>,<br />
<br />
Sie haben sich erfolgreich auf <?php echo $config->getConfig('Site', 'name'); ?> registriert.<br />
Um die Registrierung abzuschlie�en m&uuml;ssen sie folgende Seite besuchen:<br /><br />

<a href="/registrierung_bestaetigen.html?activationKey=<?php echo $activationKey; ?>">Link</a><br /><br />

Falls sie nicht weitergeleitet werden, besuchen Sie die Seite manuell und geben folgenden aktivierungs Schl&uuml;ssel ein:<br /><br />

<?php echo $activationKey; ?><br /><br />

Viele Gr&uuml;ße,<br />
Ihr <?php echo $config->getConfig('Site', 'name'); ?> Team

Falls Sie sich nicht registriert haben, ignorieren Sie bitte diese E-Mail!
If you have not registered yourself to this site then please ignore this e-mail!