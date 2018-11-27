<?php $this->view('templates/header'); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-10">
                        <h2>All Emplyees</h2>                        
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
                                <table class="table table-striped table-bordered table-hover" id="employees_dataTables">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Id</th>
                                            <th>Username</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Created Dt</th>
                                            <th>Address</th>
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
<?php $this->view('templates/footer'); ?>
<script type="text/javascript">
    
    $(document).ready(function() {

        var EmployeeHtml = '<form role="form">'+
                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<label>First Name</label>'+
                                    '<input type="text" class="form-control" name="first_name" id="first_name" />'+
                                    '<span class="spantext">Invalid First Name</span>'+
                                '</div>'+
                            '</div>'+

                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<label>Last Name</label>'+
                                    '<input type="text" class="form-control" name="last_name" id="last_name" />'+
                                    '<span class="spantext">Invalid Last Name</span>'+
                                '</div>'+
                            '</div>'+

                            '<div class="clearfix"></div>'+

                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<label>Mobile</label>'+
                                    '<input type="text" class="form-control" name="mobile" id="mobile" maxlength="10" />'+
                                    '<span class="spantext">Invalid Mobile</span>'+
                                '</div>'+
                            '</div>'+

                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<label>Email</label>'+
                                    '<input type="email" class="form-control" name="email" id="email" />'+
                                    '<span class="spantext">Invalid Email</span>'+
                                '</div>'+
                            '</div>'+
                            
                            '<div class="clearfix"></div>'+

                            '<div class="col-md-12">'+
                                '<div class="form-group">'+
                                    '<label>Address</label>'+
                                    '<textarea class="form-control" rows="3" name="address" id="address"></textarea>'+
                                    '<span class="spantext">Invalid Address</span>'+
                                '</div>'+
                            '</div>'+

                            '<div class="clearfix"></div>'+

                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<label>Login Username</label>'+
                                    '<input type="text" class="form-control" name="username" id="username" />'+
                                    '<span class="spantext">Invalid Login Username</span>'+
                                '</div>'+
                            '</div>'+

                            '<div class="col-md-6">'+
                                '<div class="form-group">'+
                                    '<label>Login Password</label>'+
                                    '<input type="password" class="form-control" name="password" id="password" />'+
                                    '<span class="spantext">Invalid Login Password</span>'+
                                '</div>'+
                            '</div>'+
                        '</form>';

        set.dataTable = $('#employees_dataTables').DataTable({
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
            // "order": [[1, 'desc']],
            "ajax": {
                "url": bu+"employees/employeeList",
                "type": "POST",
                "data": function (d) {}
            },
            "columns":  [
                {"className":'details-control',"orderable":false,"visible":false,"data":'',"defaultContent": ''},
                { "data": "id","visible":false},
                { "data": "username"},
                { "data": "first_name"},
                { "data": "last_name"},
                { "data": "mobile"},
                { "data": "email",},
                { "data": "created_dt"},
                { "data": "address"},
                { "data": "status",
                    render: function ( data, type, row ) 
                    {
                        var msg = "<a style='margin-right:10px' class='btn btn-xs btn-primary editOnwer' data-id="+row.id+">Edit</a>";
                        if(data == 1){
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-danger changeStatus' data-id="+row.id+" title='Deactivate'><i class=\"fa fa-times\"></i></a>";
                        }else{
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-success changeStatus' data-id="+row.id+" title='Activate'><i class=\"fa fa-check\"></i></a>";
                        }
                        return msg;
                    }
                },
            ],
            responsive: true
        });

        var btn_pc = '<button id="refreshAll" class="btn btn-default" type="button" title="Reload">&nbsp;<i class="fa fa-refresh"></i>&nbsp;</button>';

        $('.dataTables_filter label','#employees_dataTables_wrapper').prepend(btn_pc);
        $("#refreshAll").click(function(){ set.dataTable.ajax.reload(); });

        $('.addNewEmployee').click(function(){

            $.confirm({
                title: 'Add New Owner',
                content: OwnerHtml,
                columnClass: 'col-md-6 col-md-offset-3',
                draggable: true,
                dragWindowBorder: false,
                onOpen:function()
                {
                    check();
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
                            var param = {};
                            param.first_name = $this.$content.find('#first_name').val();
                            param.last_name = $this.$content.find('#last_name').val();
                            param.mobile = $this.$content.find('#mobile').val();
                            param.email = $this.$content.find('#email').val();
                            param.address = $this.$content.find('#address').val();
                            param.username = $this.$content.find('#username').val();
                            param.password = $this.$content.find('#password').val();
                            
                            if(!param.first_name){
                                $this.$content.find('#first_name').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.last_name){
                                $this.$content.find('#last_name').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.mobile || param.mobile.length != 10){
                                $this.$content.find('#mobile').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.email || !isEmail(param.email)){
                                $this.$content.find('#email').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.address){
                                $this.$content.find('#address').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.username){
                                $this.$content.find('#username').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.password){
                                $this.$content.find('#password').addClass('error-text').parent().find('.spantext').show();
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

                            $.post( bu+"owners/addOwner", param, function( data ) {

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

        $('.editOnwer').click(function(){
            alert('data-id');
            _this = $(this);
            alert(_this.attr('data-id'));

            $.confirm({
                title: 'Edit Owner',
                content: OwnerHtml,
                columnClass: 'col-md-6 col-md-offset-3',
                draggable: true,
                dragWindowBorder: false,
                onOpen:function()
                {
                    check();
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
                            var param = {};
                            param.first_name = $this.$content.find('#first_name').val();
                            param.last_name = $this.$content.find('#last_name').val();
                            param.mobile = $this.$content.find('#mobile').val();
                            param.email = $this.$content.find('#email').val();
                            param.address = $this.$content.find('#address').val();
                            param.username = $this.$content.find('#username').val();
                            param.password = $this.$content.find('#password').val();
                            
                            if(!param.first_name){
                                $this.$content.find('#first_name').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.last_name){
                                $this.$content.find('#last_name').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.mobile || param.mobile.length != 10){
                                $this.$content.find('#mobile').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.email || !isEmail(param.email)){
                                $this.$content.find('#email').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.address){
                                $this.$content.find('#address').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.username){
                                $this.$content.find('#username').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!param.password){
                                $this.$content.find('#password').addClass('error-text').parent().find('.spantext').show();
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

                            $.post( bu+"owners/addOwner", param, function( data ) {

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

        $('.changeStatus').click(function(){
            
            _this = $(this);
            var employeeId = _this.attr('data-id');
            var status = _this.attr('title');

            $.confirm({
                title: 'Change Status',
                content: '<br><center>Are you sure you want to '+status+' this Emplyee ?</center><br>',
                columnClass: 'col-md-4 col-md-offset-4',
                draggable: true,
                dragWindowBorder: false,
                buttons: 
                {
                    submit: 
                    {
                        text: status,
                        btnClass: 'btn-blue',
                        action: function () {
                            
                            var $this = this;
                            
                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.post( bu+"employees/changeStatus", {'employee_id':employeeId ,'status':status}, function( data ) {

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
    });
</script>