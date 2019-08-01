    </div>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/jquery-confirm.js"></script>
    <script src="assets/js/notify.js"></script>
    <!-- <script src="assets/js/morris/raphael-2.1.0.min.js"></script> -->
    <!-- <script src="assets/js/morris/morris.js"></script> -->
    <!-- <script src="assets/js/custom.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>

    <script type="text/javascript">

        var set = {};
        var ajaxRequestOn = false;
        set.btn_spin_html = '<i class="fa fa-cog fa-spin"></i> Please wait...' ;

        function check(){
            $("#mobile").keypress(function (e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    // $("#errmsg").html("Digits Only").show().fadeOut("slow");
                       return false;
                }
            });

            $(".form-control").focus(function(){
                $(this).removeClass("error-text").parent().find('.spantext').hide();//.addClass("text");
            });
        }
        
        function isEmail(email){
            var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            return regex.test(email);
        }
    </script>
</body>
</html>
