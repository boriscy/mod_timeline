<?php
/**
 * Class that helps creates the necessary JSON for the Timeline
 */
class ModTimelineHelper
{

  public $section = "";

  public function getItems($catid = "")
  {
    $catid = ($catid != "") ? "WHERE catid=$catid" : "";
    $db = &JFactory::getDBO();
    $query = "SELECT * FROM `#__content` $catid ORDER BY created ASC ";
    $db->setQuery($query);
    $items = ($items = $db->loadObjectList()) ? $items: array();
    return $items;
  }

  /**
   * Creates the JSON object to be used by the Timeline
   * @param $items array()
   * @return string
   */
  public function createJSONArray($items = array())
  {
    $items = (count($items) <= 0) ? $this->getItems(): $items;
    $json = array("dateTimeFormat" => "iso8601",
                  "events" => array());
    foreach($items as $item) {
      $tmp = array('start' =>date("c",strtotime($item->publish_up)), 'title' => $item->title, 'description' => $item->introtext);
      array_push($json['events'], $tmp);
    }

    return $json;
  }

  /**
   * Returns the max and the min date of the content
   * @return array($minDate, $maxDate)
   */
  private function getMinMax($items)
  {
    $minDate = $items[0]->publish_up;
    $maxDate = $items[count($items) -1]->publish_up;

    return array($minDate, $maxDate);
  }

}
?>
