<?php if (!View::get_var('pjaxr')->get_matching()) { ?>
    <footer id="footer">
        <div class="container">
            <a href="/imprint/">Impressum</a> | <a href="http://iekadou.com/" target="_blank">by iekadou.com</a>
        </div>
    </footer>
    <div id="map"></div>
    <script src="/js/jquery-1.11.2.min.js"></script>
    <script src="/js/jquery.pjaxr.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/toastr.min.js"></script>
    <script src="https://maps.google.de/maps/api/js?sensor=false"></script>
    <script>
        (function(PjaxrIO, $, undefined) {
            PjaxrIO.error_label = '<?php echo getTranslation("An error occurred!"); ?>';
            PjaxrIO.error_title = '<?php echo getTranslation("Whoops!"); ?>';
        }(window.PjaxrIO = window.PjaxrIO || {}, jQuery));
    </script>
    <script src="/js/main.js"></script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-56659272-1', 'auto');
      ga('set', 'anonymizeIp', true);
      ga('send', 'pageview');

    </script>
</body></html>
<?php } else { ?>
    <?php $navbar = new Template(); $navbar->load("navbar.php")->display(); ?>
    <div id="render_time">{{render_time}} s {{QUERY_COUNT}} queries on {{QUERY_COUNT}} connections</div>
    </pjaxr-body>
<?php }
