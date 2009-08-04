<style type="text/css">
.simileAjax-bubble-innerContainer{ width: 600px !important;}
#timelineContent{
  background: #FFF;
  border: 1 px solid #000;
  width: 500px;
  height: 300px;
  z-index: 100;
}
</style>
<script type="text/javascript">
var timeline_data = <?php echo json_encode($json); ?>;
//Timeline_urlPrefix = '<?php echo JURI::base(); ?>/modules/mod_timeline/tmpl/timeline_js/';
Timeline_urlPrefix = "http://static.simile.mit.edu/timeline/api-2.3/";
</script>
<script src="<?php echo JURI::base() ?>modules/mod_timeline/tmpl/Sly-min.js" type="text/javascript"></script>

<script type="text/javascript">
     Timeline_ajax_url="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_ajax/simile-ajax-api.js";
     Timeline_urlPrefix='<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/';       
     Timeline_parameters='bundle=true&forceLocale=es';
</script>

<script src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/timeline-api.js" type="text/javascript"></script>

<script src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/scripts/l10n/es/timeline.js" type="text/javascript" ></script>
<script src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/scripts/l10n/es/labellers.js" type="text/javascript" ></script>

<script type="text/javascript">
  //Timeline.CompactEventPainter.prototype.showBubble = function{ console.log(arguments); };
  // Function that fills the content
  Timeline.DefaultEventSource.Event.prototype.fillInfoBubble = function(el, theme, labeller) {
    el.innerHTML = this.getText();
    //s = Sly.find("div.simileAjax-bubble-container");
    //s.remove();
    url = "<?php echo JURI::base(); ?>index.php?option=com_content&view=article&format=ajax&id=" + this.getID();
    console.log(url);
    //s.find("#");
    console.log("%s, %s, %s",this.getID(), this.getText(), this.getDescription());//this.getID(), this.getText(), this.getDescription();
  }
  /*Timeline.DefaultEventSource.Event.prototype.fillInfoBubble = function(elmt, theme, labeller) {
    //elmt.id = "contTimeline";
    var html = '<div style="auto;width:550px;height:400px">';
    for(var i=0; i<30; i++)
      html += "Hola como estas esto es un contenido de prueba, para poder ver como se con mas HTML ahora, y ver si es que se puede expadir de forma proporcional<br/>";
    html += "</div>"
    elmt.innerHTML = html;// do whatever to fill elmt
  };*/
  var tl;
        function loadTimeline() {
          var eventSource = new Timeline.DefaultEventSource();
          var bandInfos = [
            Timeline.createBandInfo({
              eventSource: eventSource,
              date: "Jun 28 2006 00:00:00 GMT",
              width:  "70%",
              intervalUnit: Timeline.DateTime.MONTH,
              intervalPixels: <?php echo $params->get('month_width') ?>
            }),
            Timeline.createBandInfo({
              eventSource: eventSource,
              date: "Jun 28 2006 00:00:00 GMT",
              width: "30%",
              intervalUnit: Timeline.DateTime.YEAR,
              intervalPixels: <?php echo $params->get('year_width') ?>
            })
          ];
          // Sincronizacion  entre años y meses
          bandInfos[1].syncWith = 0;
          bandInfos[1].highlight = true;

          tl = Timeline.create(document.getElementById("my-timeline"), bandInfos);
          eventSource.loadJSON(timeline_data, '.');
          tl.layout();
        }
        
        var resizeTimerID = null;
        function resizeTimeline() {
          if(resizeTimerID == null) {
            resizeTimerID = window.setTimeout(function() {
                resizeTimerID = null;
                tl.layout();
            }, 500);
          }
        }

    window.onload = function () { 
      loadTimeline(); resizeTimeline();
      Sly.find("img.timeline-copyright").remove();
    }

</script>
    <div id="timelineContent" style="display:none"></div>
      <div id="my-timeline" style="height: 250px; border: 1px solid #aaa" ></div>
      

      <noscript>
      This page uses Javascript to show you a Timeline. Please enable Javascript in your browser to see the full page. Thank you.
      </noscript>

