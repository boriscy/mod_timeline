<?php
/**
 * Class that helps creates the necessary JSON for the Timeline
 */
class ModTimelineHelper
{

  public $sectionid;
  public $db;
  // Meses en español
  public $months = array("01" => "Enero", "02" => "Febrero", "03" => "Marzo",
                      "04" => "Abril", "05" => "Mayo", "06" => "Junio",
                      "07" => "Junio", "08" => "Agosto", "09" => "Septiembre",
                      "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"
                      );

  public function __construct($sectionid) {
    $this->sectionid = $sectionid;
    $this->db = &JFactory::getDBO();
  }

  /**
   * Return all items in a section
   * @param String $sectionid
   * @return array
   */
  public function getItems()
  {
    $sectionid = "WHERE sectionid={$this->sectionid}";
    $query = "SELECT * FROM `#__content` $sectionid ORDER BY created ASC";
    $this->db->setQuery($query);
    $items = ($items = $this->db->loadObjectList()) ? $items: array();
    return $items;
  }

  /**
   * Returns all categories in a section
   * @return array()
   */
  public function getCategories()
  {
    $query = "SELECT * FROM `#__categories` WHERE sectionid={$this->sectionid} ORDER BY title ASC";
    $this->db->query($query);
    $items = $this->db->loadObjectList();
    $categories = array();

    foreach($items as $item) {
      $categories[] = array('id'=> $item->id, 'title' => $item->title);
    }
    return $categories;
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
      list($date, $time) = split(" ", $item->publish_up);
      $date = split("-", $date);

      $date = '<span class="fecha">'.$date[2].' de '.$this->months[$date[1]]. ' ' . $date[0] . '</span>';
      $title = $item->title. '<br/>'. $date;
      if(preg_match('/<object[^>]+>/', $item->fulltext)) {
        $title = '<img src="' . JURI::base() . 'modules/mod_timeline/tmpl/images/youtube.png" alt="video" /> '. $title;
      }
      $tmp = array('start' =>date("c",strtotime($item->publish_up)), 'title' => $title, 'id' => $item->id);
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
