<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');
$sectionid = $params->get('sectionid');
$items = ModTimelineHelper::getItems($sectionid);
$json = ModTimelineHelper::createJSONArray($items);

//echo ":::::::::$catid";
require_once(JModuleHelper::getLayoutPath('mod_timeline'));
