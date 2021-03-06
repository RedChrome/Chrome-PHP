<!DOCTYPE html>
<html>
<head>
    <link rel="Shortcut Icon" href="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/design/chrome/favicon.ico'));?>" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/design/chrome/style/style.css'));?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/design/chrome/style/dojo.css'));?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/design/chrome/style/flexible-grids.css'));?>"  type="text/css" />
    <?php try { echo $VIEW->getRenderable(0)->render(); } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); }?>
</head>
<body>
<?php try { echo $VIEW->getRenderable(1)->render(); }catch(\Chrome\Exception $e) {$exceptionHandler->exception($e); }?>
    <div class="ym-column">
        <div class="ym-col1">
            <div class="ym-cbox">
                <div id="lNavi">
<?php foreach($VIEW->getRenderable(2)->getRenderableList() as $view) { ?>
                    <div class="Navi">
                        <div>
                            <div>
                                <div>
<?php try { ?>
                                    <h3 class="boxtitle"><?php echo $view->getViewTitle()?></h3>
                                    <div class="boxcontent"><?php echo $view->render();?>
<?php } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); } ?>
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
<?php try { ?>
                                    <h3 class="boxtitle"><?php echo $view->getViewTitle()?></h3>
                                    <div class="boxcontent"><?php echo $view->render();?>
<?php } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); } ?>
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
<?php try { echo $VIEW->getRenderable(4)->render(); }catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); } ?>
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
<?php try { echo $view->render(); } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); }?>
                     </div>
<?php } ?>
                </div>
            </div>
        </div>
    </footer>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/dojo/1.8.3/dojo/dojo.js"></script>
    <script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/javascript/dojo.js'));?>"></script>
    <script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/javascript/ganalytics.js'));?>"></script>
    <script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Resource('rel:public/javascript/form_utility.js'));?>"></script>
    <?php try { echo $VIEW->getRenderable(6)->render(); } catch(\Chrome\Exception $e) {$exceptionHandler->exception($e); }?>
    <!--<script type="text/javascript" src="'._PUBLIC.'javascript/Framework/dojo.js" djConfig="parseOnLoad:true, isDebug: true"></script>-->
</body>

</html>