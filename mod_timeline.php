<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');
$sectionid = $params->get('sectionid');
$interval = 300;// $params->get('interval') ? $params->get('interval') : 200;

$tm = new ModTimelineHelper($sectionid);
$json = array();
//$tm->getItems();
$categories = $tm->getCategories();

require_once(JModuleHelper::getLayoutPath('mod_timeline'));
