<?php
/**
* @package   yoo_phoenix Template
* @version   1.5.2 2009-07-02 16:34:50
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// include YOOmenu system
require_once(dirname(__FILE__).'/yoomenu.php');

// render YOOmenu
$yoomenu = &YOOMenu::getInstance();
$yoomenu->setParams($params);
$yoomenu->render($params, 'YOOMenuDefaultDecorator');

?>