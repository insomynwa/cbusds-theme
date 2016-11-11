<!DOCTYPE html>
<html>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<title><?php wp_title(''); ?><?php if(wp_title('',false)) { echo ' :'; } ?><?php bloginfo('name'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php bloginfo('description'); ?>">

<?php wp_head(); ?>
<script>
// conditionizr.com
// configure environment tests
conditionizr.config({
    assets: '<?php echo get_template_directory_uri(); ?>',
    tests: {}
});
</script>
</head>
<body>

<div id="content-wrapper" class="w3-container">
  <!-- HEADER -->
  <div id="content-header" class="w3-row">
    <div id="logo" class="w3-col l3">
      <img src="http://192.168.1.58:81/WPDEV/wp-content/themes/cbusds-theme/css/cbus-header.png">
    </div>
    <!-- <div id="sponsor" class="w3-col l5"><p></p></div> -->
    <div id="widget-1" class="w3-col l4 w3-right">
      <?php dynamic_sidebar( 'cbus_widget_1' ); ?>
    </div>
  </div> <!-- END HEADER -->

  <!-- BODY -->
  <div id="content-body" class="w3-row">
    <div id="left-body" class="w3-col l8">
      <div id="video-area" class="w3-card-4 w3-round">
        <iframe src="https://www.youtube.com/embed/i_r3z1jYHAc?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
      </div>
      <div id="socmed-area" class="w3-card-4 w3-round"></div>
    </div>
    <div id="right-body" class="w3-col l4">
      <div id="widget-2" class="w3-card-4 w3-round">
        <h3>Chart</h3>
        <?php echo do_shortcode('[gdoc key="1WmVYfFClgVn6gWdlWPRTRyjDFmSu24QL3kaKO-wZHbc" chart="Line" ]');
        ?>
      </div>
      <div id="widget-3" class="w3-card-4 w3-round"><?php dynamic_sidebar( 'cbus_widget_3' ); ?></div>
      <div id="widget-4" class="w3-card-4 w3-round">
        <div id="widget-4-content" class="w3-container"></div>
      </div>
    </div>
  </div> <!-- END BODY -->

  <div id="content-footer" class="w3-container">
    <p id="running-text"></p>
  </div>

</div>
<script type="text/javascript">
  jQuery(document).ready(function($) {

    var h = $("#video-area iframe").height();
    var w = 16 * h / 9;
    $("#video-area iframe").width(w);

    var cur_running_text = "";
    var cur_duration = 10000;
    function doRunningText() {
      $("#running-text").html(cur_running_text);
      $("#running-text").marquee(
        {
          duration:cur_duration
        }
      );
    }
    function loadRunningText() {
      var data = {
        action: "GetRunningText"
      };
      $.get (
        cbusds_ajax.ajaxurl,
        data,
        function(response) {
          var result = JSON.parse(response);
          var doRun = false;
          if(result.running_text.text !== cur_running_text) {
            cur_running_text = result.running_text.text;
            doRun = true;
          }
          if(result.running_text.duration !== cur_duration) {
            cur_duration = result.running_text.duration;
            doRun = true;
          }
          if(doRun) {
            doRunningText();
          }
        }
      );
    }
    loadRunningText();
    setInterval(function(){ loadRunningText(); }, 10000);

    var cur_info = [ "", "", "", "", "" ];
    var slideIndex = 0;
    var timeoutHandler;
    function doInfo() {
      var i = 0;
      var x = $("p.info-slide");
      for( i=0; i<x.length; i++) {
        $(x[i]).css('display','none');
      }
      slideIndex++;
      if( slideIndex > x.length) { slideIndex = 1; }
      $(x[slideIndex-1]).css('display','block');
      timeoutHandler = setTimeout(doInfo, 5000);
    }
    function loadInfo() {
      var data = {
        action: 'GetInfo'
      }
      $.get(
        cbusds_ajax.ajaxurl,
        data,
        function(response) {
          var result = JSON.parse(response);
          var doRun = false;
          for(var i=0; i<(result.info).length; i++){
            if( result.info[i] !== cur_info[i]) {
              doRun = true;
              cur_info[i] = result.info[i];
            }
          }

          if(doRun) {
            $("#widget-4-content").html("");
            $("#widget-4-content").append("<h3>Info</h3>");
            for(var i=0; i<(cur_info).length; i++){
              if(cur_info[i] != "" ) {
                $("#widget-4-content").append("<p class='info-slide'>" + cur_info[i] + "</p>");
              }
            }
            window.clearTimeout(timeoutHandler);
            doInfo();
          }
        }
      );
    }
    loadInfo();
    setInterval(function(){ loadInfo(); }, 10000);

    var cur_statuses = [0,0];
    function loadSocmed() {
      var data = {
        action: 'GetMyTweet'
      }
      $.get(
        cbusds_ajax.ajaxurl,
        data,
        function(response) {
          var result = JSON.parse(response);
          var strTag = "";
          var doRun = false;
          if(result.statuses.length > 0) {
            for(var i=0; i< (result.statuses).length; i++) {
              if(cur_statuses[i] != result.statuses[i].id) {
                if( i==0 ) strTag += "<div class='w3-row first'>";
                else strTag += "<div class='w3-row'>";
                  strTag += "<div class='w3-col l3 w3-container'>";
                    strTag += "<img class='prof-pict' src='" + result.statuses[i].user.profile_image_url + "' >";
                    strTag += "<p class='userid'>@" + result.statuses[i].user.screen_name + "</p>";
                  strTag += "</div>";
                  strTag += "<div class='w3-col l9 w3-container'>";
                    strTag += "<p class='created'>" + parseTwitterDate(result.statuses[i].created_at) + "</p>";
                    strTag += "<p class='comment'>" + result.statuses[i].text + "</p>";
                  strTag += "</div>";
                strTag += "</div>";
                cur_statuses[i] = result.statuses[i].id;
                doRun = true;
              }
            }
            if(doRun) {
              $("#socmed-area").html("<h3>Twitter</h3>" + strTag).fadeIn("800");
            }
            
          }
        }
      );
    }
    loadSocmed();
    setInterval(function(){ loadSocmed(); }, 10000);

    var youtube_source = "";
    function loadYoutube() {
      var data = {
        action: 'GetYoutube'
      }
      $.get(
        cbusds_ajax.ajaxurl,
        data,
        function(response) {
          var result = JSON.parse(response);
          var doRun = false;

          if(result.youtube.status) {
            if(result.youtube.link !== youtube_source ) {
              $("#video-area iframe").attr("src", result.youtube.link + "?&rel=0&amp;controls=0&amp;showinfo=0");
              youtube_source = result.youtube.link;//autoplay=1
            }
          }
        }
      );
    }
    loadYoutube();
    setInterval(function(){ loadYoutube(); }, 10000);

    function parseTwitterDate(tdate) {
        var system_date = new Date(Date.parse(tdate));
        var user_date = new Date();
        if (K.ie) {
            system_date = Date.parse(tdate.replace(/( \+)/, ' UTC$1'))
        }
        var diff = Math.floor((user_date - system_date) / 1000);
        if (diff <= 1) {return "just now";}
        if (diff < 20) {return diff + " seconds ago";}
        if (diff < 40) {return "half a minute ago";}
        if (diff < 60) {return "less than a minute ago";}
        if (diff <= 90) {return "one minute ago";}
        if (diff <= 3540) {return Math.round(diff / 60) + " minutes ago";}
        if (diff <= 5400) {return "1 hour ago";}
        if (diff <= 86400) {return Math.round(diff / 3600) + " hours ago";}
        if (diff <= 129600) {return "1 day ago";}
        if (diff < 604800) {return Math.round(diff / 86400) + " days ago";}
        if (diff <= 777600) {return "1 week ago";}
        return "on " + system_date;
    }
    // from http://widgets.twimg.com/j/1/widget.js
    var K = function () {
        var a = navigator.userAgent;
        return {
            ie: a.match(/MSIE\s([^;]*)/)
        }
    }();
  });
</script>
<?php wp_footer(); ?>
</body>
</html>