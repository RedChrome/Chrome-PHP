<!DOCTYPE html PUBLIC \'-//W3C//DTD XHTML 1.0 Transitional//EN\' \'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\'>
<!-- Document created by chrome-php -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <link rel="Shortcut Icon" href="public/design/chrome/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="public/design/chrome/style/style.css" type="text/css" />
    <link rel="stylesheet" href="public/design/chrome/style/dojo.css" type="text/css" />
    <?php echo $VIEW->getRenderable(0)->render(); ?>
</head>
<body>
<?php echo $VIEW->getRenderable(1)->render(); ?>
    <div class="ym-column">
        <div class="ym-col1">
            <div class="ym-cbox">
                <div id="lNavi">
<?php foreach($VIEW->getRenderable(2)->getRenderableList() as $view) { ?>
                    <div class="Navi">
                        <div>
                            <div>
                                <div>
                                    <h3 class="boxtitle"><?php echo $view->getViewTitle()?></h3>
                                    <div class="boxcontent"><?php echo $view->render();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } ?>
                </div>
            </div>
        </div>
        <div class="ym-col2">
            <div class="ym-cbox">
                <div id="rNavi">
<?php foreach($VIEW->getRenderable(3)->getRenderableList() as $view) { ?>
                    <div class="Navi">
                        <div>
                            <div>
                                <div>
                                    <h3 class="boxtitle"><?php echo $view->getViewTitle()?></h3>
                                    <div class="boxcontent"><?php echo $view->render();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } ?>
                </div>
            </div>
        </div>
        <div class="ym-col3">
            <div class="ym-cbox">
<?php echo $VIEW->getRenderable(4)->render(); ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="ym-column">
            <div class="ym-col1">
                <div class="ym-cbox">
                </div>
            </div>
            <div class="ym-col2">
                <div class="ym-cbox">
                </div>
            </div>
            <div class="ym-col3">
                <div class="ym-cbox">
<?php foreach($VIEW->getRenderable(5)->getRenderableList() as $view) { ?>
                    <div class="ym-wbox">
<?php echo $view->render(); ?>
                     </div>
<?php } ?>
                </div>
            </div>
        </div>
    </footer>
</body>
<?php echo $VIEW->getRenderable(6)->render(); ?>
<!--<script type="text/javascript" src="'._PUBLIC.'javascript/Framework/dojo.js" djConfig="parseOnLoad:true, isDebug: true"></script>-->
<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6.1/dojo/dojo.xd.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo _PUBLIC;?>javascript/dojo.js"></script>
<script type="text/javascript" src="<?php echo _PUBLIC;?>javascript/ganalytics.js"></script>
<script type="text/javascript" src="<?php echo _PUBLIC;?>javascript/chrome.js"></script>
<script type="text/javascript" src="<?php echo _PUBLIC;?>javascript/form_utility.js"></script>
</html>