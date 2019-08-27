<?php $this->view('admin/templates/header'); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-10">
                        <h2>All Users</h2>                        
                    </div>
                    <div class="col-md-2">
                        <!-- <a class="btn btn-primary pull-right addNewOwner" style="margin-top: 20px;">Add New</a> -->
                        <!-- <h3>All Owners</h4>                         -->
                        <!-- <span class="pull-right">test</span>    -->
                    </div>
                </div>
                <hr />
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="users_dataTables">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Action</th> 
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
               
    </div>
<?php $this->view('admin/templates/footer'); ?>
<link href="<?php echo base_url();?>assets/css/admin/multiselect.css" rel="stylesheet" />
<script src="<?php echo base_url();?>assets/js/admin/multiselect.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {

        set.dataTable = $('#users_dataTables').DataTable({
            "processing": true,
            "serverSide": true,
            "stripeClasses": [ 'odd-row', 'even-row' ],
            "language": {
                "search": "_INPUT_",
                "sLengthMenu": "_MENU_",
                "searchPlaceholder": "Search...",
                "paginate": {
                    "previous": "",
                    "next":""
                }
            },
            "order": [[1, 'desc']],
            "ajax": {
                "url": bu+"admin/users/usersList",
                "type": "POST",
                "data": function (d) {}
            },
            "columns":  [
                {"className":'details-control',"orderable":false,"visible":false,"data":'',"defaultContent": ''},
                { "data": "id","visible":false},
                { "data": "first_name",
                    render: function ( data, type, row )
                    {
                        return data + ' ' + row.last_name;
                    }
                },
                { "data": "mobile"},
                { "data": "email"},
                { "data": "id",
                    render: function ( data, type, row ) 
                    {
                        var msg = "<a style='margin-right:10px' class='btn btn-xs btn-primary editUser'><i class='fa fa-pencil'></i></a>";
                        if(row.status == 1){
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-danger changeUserStatus' data-status='0' title='Disable'><i class=\"fa fa-times\"></i></a>";
                        }else{
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-success changeUserStatus' data-status='1' title='Enable'><i class=\"fa fa-check\"></i></a>";                            
                        }

                        msg += "<a style='margin-right:10px' class='btn btn-xs btn-success openPerms'>Permissions</a>"
                        
                        return msg;
                    }
                },
            ],
            responsive: true
        });

        var btn_pc = '<button id="addNewUser" class="btn btn-default" type="button" title="Add new">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>';
        btn_pc += '&nbsp;&nbsp;<button id="refreshAll" class="btn btn-default" type="button" title="Reload">&nbsp;<i class="fa fa-refresh"></i>&nbsp;</button>';

        $('.dataTables_filter','#users_dataTables_wrapper').prepend(btn_pc);
        $("#refreshAll").click(function(){ set.dataTable.ajax.reload(); });

        $('#addNewUser').click(function(){

            $.confirm({
                title: 'Add New',
                content: '<center>'+set.btn_spin_html+'</center>',
                columnClass: 'col-md-12',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore:function(){

                    var $this = this;
                    $.get( bu+"admin/users/get_new_user_html", function( data ) {
                        $this.setContent('<br>'+data);
                    });
                },
                buttons: 
                {
                    submit: 
                    {
                        text: 'Submit',
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

                            if(!$this.$content.find('#mobile').val() || $this.$content.find('#mobile').val().length < 10){
                                $this.$content.find('#mobile').addClass('error-text').parent().find('.spantext').show();
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


                            if(!$this.$content.find('#password').val() || $this.$content.find('#password').val().length < 6){
                                $this.$content.find('#password').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if($this.$content.find('#password').val() != $this.$content.find('#confirm_password').val()){
                                $this.$content.find('#confirm_password').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(error){
                                return false;
                            }
                            
                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.post( bu+"admin/users/createNewUser", $('#newUserForm').serialize(), function( data ) {

                                $this.buttons.submit.enable();
                                $this.buttons.submit.setText('submit');
                                $this.buttons.cancel.enable();
                                
                                ajaxRequestOn = false;
                                
                                data = $.parseJSON(data);
                                if(data.status == "201"){

                                    var ids = data.msg.join(',#');
                                    $this.$content.find('#'+ids).addClass('error-text').parent().find('.spantext').show();
                                }

                                if(data.status == '202'){

                                    $.notify(data.msg);
                                }

                                if(data.status == '200'){

                                    $.notify(data.msg, "success");
                                    $this.close();
                                    set.dataTable.ajax.reload();
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

        $("#users_dataTables").on("click",".editUser",function(){
            
            _this = $(this);
            var rowData = set.dataTable.row(_this.parent().parent()).data();

            $.confirm({
                title: 'Profile!',
                content: '<br><center>Get data.....</center><br>',
                columnClass: 'col-md-8 col-md-offset-2',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore:function(){

                    var $this = this;
                    $.post( bu+"admin/users/get_profile_html", {u_id:rowData.id}, function( data ) {
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
                                    set.dataTable.ajax.reload();
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

        $("#users_dataTables").on("click",".changeUserStatus",function(){
            
            _this = $(this);
            var Rstatus = _this.attr('data-status');
            var rowData = set.dataTable.row(_this.parent().parent()).data();

            var btnClass = '';
            if(Rstatus == 0){
                Rstatus = 'Disable';
                btnClass = 'btn-danger';
            }else{
                Rstatus = 'Enable';
                btnClass = 'btn-blue';
            }

            $.confirm({
                title: 'Confirm!',
                content: '<br><center>Are you sure you want to '+Rstatus+' this user ?</center><br>',
                columnClass: 'col-md-4 col-md-offset-4',
                draggable: true,
                dragWindowBorder: false,
                buttons: 
                {
                    submit: 
                    {
                        text: Rstatus,
                        btnClass: btnClass,
                        action: function () {
                            
                            var $this = this;
                            
                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.post( bu+"admin/users/changeUserStatus", {'usr_id':rowData.id,'status':Rstatus}, function( data ) {

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
                                    set.dataTable.ajax.reload();
                                    $this.close();
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

        $("#users_dataTables").on("click",".openPerms",function(){
            
            _this = $(this);
            var rowData = set.dataTable.row(_this.parent().parent()).data();
            
            set.docData = $.confirm({
                title: rowData.first_name+' '+rowData.last_name+"'s Perms",
                content: '<center>'+set.btn_spin_html+'</center>',
                columnClass: 'col-md-8 col-md-offset-2',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore: function(){

                    var $this = this;
                    $.post( bu+"admin/users/getUserPerms", {usr_id: rowData.id}, function( data ) {
                        $this.setContent('<br>'+data);
                        approveFuction();
                    });


                },
                buttons: 
                {
                    submit: 
                    {
                        text: 'update',
                        btnClass: 'btn-blue',
                        action: function () {
                            
                            var $this = this;
                                
                            var param = new FormData($("#userPermForm")[0]);

                            if(!$this.$content.find('#user_id').val()){
                                $.notify('Somthing went wrong!');
                                return false;
                            }

                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.ajax({
                                url: bu+"admin/users/updateUserPerms",
                                data: param,
                                type: 'POST',
                                dataType: 'json',
                                async: false,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (resp) {
            
                                    $this.buttons.submit.enable();
                                    $this.buttons.submit.setText('submit');
                                    $this.buttons.cancel.enable();
                                    
                                    ajaxRequestOn = false;
                                    
                                    var data = resp;
                                    if(data.status == "201"){

                                        var ids = data.msg.join(',#');
                                        $this.$content.find('#'+ids).addClass('error-text').parent().find('.spantext').show();
                                    }

                                    if(data.status == '202'){

                                        $.notify(data.msg);
                                    }

                                    if(data.status == '200'){

                                        $.notify(data.msg, "success");
                                        set.dataTable.ajax.reload();
                                        $this.close();
                                    }
                                }
                            })
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
    });
</script>