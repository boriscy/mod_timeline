<?php
/**
 * Class that helps creates the necessary JSON for the Timeline
 */
class ModTimelineHelper
{

  public $section = "";

 
  public static function getItems($sectionid = "")
  {
    $sectionid = ($sectionid != "") ? "WHERE sectionid=$sectionid" : "";
    $db = &JFactory::getDBO();
    $query = "SELECT * FROM `#__content` $sectionid ORDER BY created ASC ";
    $db->setQuery($query);
    $items = ($items = $db->loadObjectList()) ? $items: array();
    return $items;
  }

  /**
   * Creates the JSON object to be used by the Timeline
   * @param $items array()
   * @return string
   */
  public static function createJSONArray($items = array())
  {
    $items = (count($items) <= 0) ? ModTimelineHelper::getItems(): $items;
    $json = array("dateTimeFormat" => "iso8601",
                  "events" => array());
    $months = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo",
                        "04" => "Abril", "05" => "Mayo", "06" => "Junio",
                        "07" => "Junio", "08" => "Agosto", "09" => "Septiembre",
                        "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"
                        );

    foreach($items as $item) {
      list($date, $time) = split(" ", $item->publish_up);
      $date = split("-", $date);
      $date = '<span class="fecha">'.$date[2].' de '.$months[$date[1]]. ' ' . $date[0] . '</span>';
      $tmp = array('start' =>date("c",strtotime($item->publish_up)), 'title' => $item->title .' '. $date, 'id' => $item->id);
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
