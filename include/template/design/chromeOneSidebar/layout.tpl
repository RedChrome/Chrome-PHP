<!DOCTYPE html>
<html>
<head>
    <link rel="Shortcut Icon" href="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/design/chrome_one_sidebar/favicon.ico'));?>" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/design/chrome_one_sidebar/style/flexible-grids.css'));?>"  type="text/css" />
    <link rel="stylesheet" href="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/design/chrome_one_sidebar/style/style.css'));?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/design/chrome_one_sidebar/style/dojo.css'));?>" type="text/css" />
    <?php try { echo $VIEW->getRenderable(0)->render(); } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); }?>
</head>
<body>
<?php try { echo $VIEW->getRenderable(1)->render(); }catch(\Chrome\Exception $e) {$exceptionHandler->exception($e); }?>

<main>
    <div class="ym-wrapper">
        <div class="ym-wbox">
            <div class="ym-grid linearize-level-1 ym-equalize">
                <div class="ym-g62 ym-gl content">
                    <div class="ym-gbox-left ym-clearfix">
<?php foreach($VIEW->getRenderable(2)->getRenderableList() as $view) { ?>
                        <div class="module">
<?php try { echo $view->render(); }catch(\Chrome\Exception $e) {$exceptionHandler->exception($e); }?>
                        </div>
<?php } ?>
                    </div>
                </div>
                <aside class="ym-g38 ym-gr">
                    <div class="ym-gbox-right ym-clearfix">
                        <div id="rNavi" class="Navi">
<?php foreach($VIEW->getRenderable(3)->getRenderableList() as $key => $view) { ?>
                            <div class="boxsidebar" <?php if($VIEW->getRenderable(3)->getRenderableList()->isLast()) echo 'style="border-bottom: none"';?>>
<?php try { ?>
                                <h3 class="boxtitle"><?php echo $view->getViewTitle()?></h3>
                                <div class="boxcontent"><?php echo $view->render();?>
<?php } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); } ?>
                                </div>
                            </div>
<?php } ?>
                        </div>
                    </div>
               </aside>
            </div>
        </div>
    </div>
</main>


<footer style="background-color: #fff">
    <div class="ym-wrapper">
    	<div class="ym-wbox">

<?php foreach($VIEW->getRenderable(4)->getRenderableList() as $view) { ?>
                    <div class="ym-wbox">
<?php try { echo $view->render(); } catch(\Chrome\Exception $e) { $exceptionHandler->exception($e); }?>
                     </div>
<?php } ?>

        </div>
    </div>
</footer>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/dojo/1.10.4/dojo/dojo.js"></script>
<script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/javascript/dojo.js'));?>"></script>
<script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/javascript/form_utility.js'));?>"></script>
<script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/javascript/third-party/jstz.min.js'));?>"></script>
<script type="text/javascript" src="<?php echo $LINKER->get(new \Chrome\Resource\Relative('public/javascript/timezone.js'));?>"></script>
<?php try { echo $VIEW->getRenderable(5)->render(); } catch(\Chrome\Exception $e) {$exceptionHandler->exception($e); }?>
<!--<script type="text/javascript" src="'._PUBLIC.'javascript/Framework/dojo.js" djConfig="parseOnLoad:true, isDebug: true"></script>-->
</body>
</html>