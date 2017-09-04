<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>The jin hu</b> Tourist System | Version 1.0
    </div>
    <strong>Copyright &copy; 2017-2018 <a href="<?php echo base_url(); ?>">Thejinhu</a>.</strong> All rights reserved.
</footer>

<!-- jQuery UI 1.11.2 -->
<!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script> -->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    var windowURL = window.location.href;
    pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
    var x = $('a[href="' + pageURL + '"]');
    x.addClass('active');
    x.parent().addClass('active');
    var y = $('a[href="' + windowURL + '"]');
    y.addClass('active');
    y.parent().addClass('active');
    //datepicker plugin
    //link
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    $(document).one('ajaxloadstart.page', function (e) {
        autosize.destroy('textarea[class*=autosize]')

        $('.limiterBox,.autosizejs').remove();
        $('.daterangepicker.dropdown-menu,.colorpicker.dropdown-menu,.bootstrap-datetimepicker-widget.dropdown-menu').remove();
    });
    $(document).ready(function () {
        var main_page_width = window.innerWidth;
        if (main_page_width <= 1450)
            $("#main_page_body").addClass("sidebar-collapse");
        else
            $("#main_page_body").removeClass("sidebar-collapse");
        $(window).resize(function () {
            main_page_width = window.innerWidth;
            if (main_page_width <= 1450)
                $("#main_page_body").addClass("sidebar-collapse");
            else
                $("#main_page_body").removeClass("sidebar-collapse");
        });

    });

</script>
</body>
</html>