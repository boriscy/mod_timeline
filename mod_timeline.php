<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');
$catid = $params->get('catid');
$items = ModTimelineHelper::getItems($catid);
$json = ModTimelineHelper::createJSONArray($items);


require_once(JModuleHelper::getLayoutPath('mod_timeline'));
