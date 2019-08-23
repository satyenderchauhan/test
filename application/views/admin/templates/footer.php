    </div>

    <script src="<?php echo base_url();?>assets/js/admin/jquery-1.10.2.js"></script>
    <script src="<?php echo base_url();?>assets/js/admin/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/admin/jquery.metisMenu.js"></script>
    <script src="<?php echo base_url();?>assets/js/jquery-confirm.js"></script>
    <script src="<?php echo base_url();?>assets/js/notify.js"></script>
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

        $('.openProfile').click(function(){
            
            _this = $(this);
            $.confirm({
                title: 'Profile!',
                content: '<br><center>Get data.....</center><br>',
                columnClass: 'col-md-8 col-md-offset-2',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore:function(){

                    var $this = this;
                    $.post( bu+"admin/users/get_profile_html", {u_id:'1'}, function( data ) {
                        $this.setContent('<br>'+data);
                    });
                },
                buttons: 
                {
                    submit: 
                    {
                        text: 'Update',
                        btnClass: 'btn-blue',
                        action: function () {
                            
                            var $this = this;
                            var error = false;
                            
                            if(!$this.$content.find('#first_name').val()){
                                $this.$content.find('#first_name').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#last_name').val()){
                                $this.$content.find('#last_name').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#email').val()){
                                $this.$content.find('#email').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!isEmail($this.$content.find('#email').val())){
                                $this.$content.find('#email').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if($this.$content.find('#password').attr('disabled') != 'disabled'){

                                if(!$this.$content.find('#password').val() || $this.$content.find('#password').val().length < 6){
                                    $this.$content.find('#password').addClass('error-text').parent().find('.spantext').show();
                                    error = true;
                                }
                            }

                            if(error){
                                return false;
                            }
                            
                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.post( bu+"admin/users/updateProfile", $('#profileForm').serialize(), function( data ) {

                                $this.buttons.submit.enable();
                                $this.buttons.submit.setText('submit');
                                $this.buttons.cancel.enable();
                                
                                ajaxRequestOn = false;
                                
                                data = $.parseJSON(data);
                                if(data.status == '201'){

                                    $.notify(data.msg);
                                }

                                if(data.status == '200'){

                                    $.notify(data.msg, "success");
                                    $this.close();
                                    location.reload(true);
                                }
                            });

                            return false;
                        }
                    },
                    cancel: 
                    {
                        text: 'Close',
                        btnClass: 'btn-default'
                    }
                }
            });
        });

    </script>
</body>
</html>
