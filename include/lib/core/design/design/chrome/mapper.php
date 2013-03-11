<?php

/**
 * CHROME-PHP CMS
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the Creative Commons license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/3.0/
 * If you did not receive a copy of the license AND are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@chrome-php.de so we can send you a copy immediately.
 *
 * @category   CHROME-PHP
 * @package    CHROME-PHP
 * @subpackage Chrome.Design
 * @author     Alexander Book <alexander.book@gmx.de>
 * @copyright  2012 Chrome - PHP <alexander.book@gmx.de>
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons
 * @version    $Id: 0.1 beta <!-- phpDesigner :: Timestamp [08.03.2013 16:00:21] --> $
 * @link       http://chrome-php.de
 */

if(CHROME_PHP !== true)
    die();

class Chrome_Design_Mapper_Chrome extends Chrome_Design_Mapper_Database_Static_Abstract
{
    public function __construct(Chrome_Application_Context_Interface $appContext) {
        $this->_model = new Chrome_Model_Design_Mapper_Static($appContext);
    }

    protected function _map(Chrome_Design_Renderable_Container_Interface $container) {
        switch(strtolower($container->getPosition())) {

            case 'footer': {
                Chrome_Design_Composite_Footer::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'bottom' : {
                Chrome_Design_Composite_Bottom::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'head': {
                Chrome_Design_Composite_Head::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'header': {
                Chrome_Design_Composite_Header::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'left_box': {
                Chrome_Design_Chrome_Composite_Left_Box::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'right_box': {
                Chrome_Design_Chrome_Composite_Right_Box::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'content': {
                Chrome_Design_Chrome_Composite_Content::getInstance()->getComposite()->addView($container->getRenderable());
                break;
            }

            case 'ajax': {
                Chrome_Design_Composite_Laconic::getInstance()->getComposite()->addView($container->getRenderable());
            }

            default: {
                // do nothing
            }
        }
    }
}