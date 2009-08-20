<script src="<?php echo JURI::base() ?>modules/mod_timeline/tmpl/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
// Tabs script
$(function () {
  var tabContainers = $('div.tabs > div');
  tabContainers.hide().filter(':first').show();
  
  $('div.tabs ul.tabNavigation a').click(function () {
    tabContainers.hide();
    tabContainers.filter(this.hash).show();
    $('div.tabs ul.tabNavigation a').removeClass('selected');
    $(this).addClass('selected');
    return false;
  }).filter(':first').click();
});
</script>


<script type="text/javascript">
var timeline_data = <?php echo json_encode($json); ?>;
//Timeline_urlPrefix = '<?php echo JURI::base(); ?>/modules/mod_timeline/tmpl/timeline_js/';
Timeline_urlPrefix = "http://static.simile.mit.edu/timeline/api-2.3/";
</script>

<script type="text/javascript">
     Timeline_ajax_url="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_ajax/simile-ajax-api.js";
     Timeline_urlPrefix='<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/';       
     Timeline_parameters='bundle=true&forceLocale=es';
</script>

<script src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/timeline-api.js" type="text/javascript"></script>

<script src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/scripts/l10n/es/timeline.js" type="text/javascript" ></script>
<script src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/timeline_js/scripts/l10n/es/labellers.js" type="text/javascript" ></script>

<link rel="stylesheet" href="<?php echo JURI::base() ?>modules/mod_timeline/tmpl/style.css" type="text/css" />

<!-- DIVs used for the modal window -->
<div id="dialog" class="window">  
  <!-- close button is defined as close class -->  
  <a href="#" class="close"><img src="<?php echo JURI::base(); ?>modules/mod_timeline/tmpl/images/x.png" alt="Cerrar" /></a>
  <h1>Título</h1>

  <div id="tabs" class="tabs">
    <ul class="tabs">
      <li><a href="#tabs-desc" class="selected-tab">Descripción</a></li>
      <li><a href="#tabs-photo">Foto</a></li>
      <li><a href="#tabs-video">Video</a></li>
      <li><a href="#tabs-doc">Documento</a></li>
      <li><a href="#tabs-graph">Gráfico</a></li>
    </ul>
    <div id="tabs-desc" class="tab">1</div>
    <div id="tabs-photo" class="tab">2</div>
    <div id="tabs-video" class="tab">3</div>
    <div id="tabs-doc" class="tab">4</div>
    <div id="tabs-graph" class="tab">5</div>
  </div>
</div>  
<!-- Do not remove div#mask, because you'll need it to fill the whole screen -->    
<div id="mask"></div>
<script type="text/javascript">
/***
 * Script for modal window
 */
function modalWindow(id) {

  var id = id || '#dialog';
  //Get the window height and width
  var winH = $(window).height();
  var winW = $(window).width();
  $('#mask').show();
  //Set the popup window to center
  $(id).css('top', $(window).scrollTop() + 50);
  //$(id).css('top',  winH/2-$(id).height()/2);
  $(id).css('left', winW/2-$(id).width()/2);

  //transition effect
  $(id).fadeIn(600);
};
function closeModal() {
  $('#dialog').hide();

  $('#mask').hide();
}

jQuery(document).ready(function() {

  $('#dialog a.close').click(function() {
    closeModal();
    return false;
  });
  // mask options
  var w = $(window).width(), h = $(document).height();
  $('#mask').css({ width: w + "px", height: h + "px"}).fadeTo("fast", 0.6)
  .click(function(){ closeModal(); return false; }); // Hide mask

  $('#dialog ul li a').click(function() {
    $('#dialog ul li a').removeClass('selected-tab');
    $(this).addClass('selected-tab');
    $('#dialog .tab').hide();
    var id = $(this).attr("href");
    $(id).show();
    return false;
  });

});
</script>

<script type="text/javascript">
  // Put the first tab available
  function resetTabs(id) {
    id = id || '#dialog';
    $(id + ' .tab:first').show().siblings("div.tab").hide();
    $(id + ' ul.tabs a:first').addClass('selected-tab');
    $(id + ' ul li a:not(:first)').removeClass("selected-tab"); 
  }

  var theme = Timeline.ClassicTheme.create(); // create the theme
      theme.event.label.height = 20;

  // Function that fills the content
  Timeline.DefaultEventSource.Event.prototype.fillInfoBubble = function(el, theme, labeller) {
    el.innerHTML = this.getText();
    //s = Sly.find("div.simileAjax-bubble-container");
    //s.remove();
    url = "<?php echo JURI::base(); ?>index.php?option=com_content&view=article&format=ajax&id=" + this.getID();
    $.getJSON(url, function(data){
      resetTabs();
      setTimeout(function (){ $('div.simileAjax-bubble-container').remove() }, 300);
      $('#dialog h1').html(data.title);
      delete(data.title);
      var noData = '<h2 class="nodata">Ho hay Datos</h2>';
      for(var k in data) {
        $('#tabs-' + k).html(data[k]);
      }
      var props = ["desc", "doc", "graph"];

      for(var i=0, l = props.length; i < l; i++) {
        if($('#tabs-' + props[i]).text() == "") {
          $('#tabs-'+props[i]).html(noData);
        }
      }
      
      if($('#tabs-video').find("object").length <= 0 ) {
        $('#tabs-video').html(noData);
      }

      if($('#tabs-photo').find("img").length <= 0){
        $('#tabs-photo').html(noData);
      }

      modalWindow();
    });

    return false;
  }

  var tl;
        function loadTimeline() {
          var eventSource = new Timeline.DefaultEventSource();
          var bandInfos = [
            Timeline.createBandInfo({
              eventSource: eventSource,
              date: "Jan 28 2006 00:00:00 GMT",
              width:  "90%",
              theme: theme,
              intervalUnit: Timeline.DateTime.MONTH,
              intervalPixels: <?php echo $params->get('month_width') ?>
            }),
            Timeline.createBandInfo({
          //    eventSource: eventSource,
              date: "Jan 28 2006 00:00:00 GMT",
              width: "10%",
              intervalUnit: Timeline.DateTime.MONTH,
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
      $("img.timeline-copyright").remove();
    }

</script>
    <div id="timelineContent" style="display:none"></div>
      <div id="my-timeline" style="height: <?php echo $params->get('height') ?>px; border: 1px solid #aaa" ></div>
      

      <noscript>
      This page uses Javascript to show you a Timeline. Please enable Javascript in your browser to see the full page. Thank you.
      </noscript>

