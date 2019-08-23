<?php $this->view('admin/templates/header'); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-10">
                        <h2>All Employees</h2>                        
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
                                <table class="table table-striped table-bordered table-hover" id="employee_dataTables">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>City</th> 
                                            <th>Experience</th> 
                                            <th>Approx Salary</th> 
                                            <th>Salary</th> 
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

        set.dataTable = $('#employee_dataTables').DataTable({
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
                "url": bu+"admin/employees/employeeList",
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
                { "data": "city"},
                { "data": "experience",
                    render: function ( data, type, row )
                    {
                        return data + ' Years';
                    }
                },
                { "data": "approx_salary"},
                { "data": "salary"},
                { "data": "id",
                    render: function ( data, type, row ) 
                    {
                        var msg = "<a style='margin-right:10px' class='btn btn-xs btn-primary editEmployee' data-id="+row.id+">Edit</a>";
                        if(row.status == 1){
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-danger changeEmployeeStatus' data-status='0' title='Disable'><i class=\"fa fa-times\"></i></a>";
                        }else{
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-success changeEmployeeStatus' data-status='1' title='Enable'><i class=\"fa fa-check\"></i></a>";                            
                        }
                        
                        msg += "<a style='margin-right:10px' class='btn btn-xs btn-default openEmpDocs' title='Enable'><i class=\"fa fa-file\"></i></a>";

                        return msg;
                    }
                },
            ],
            responsive: true
        });

        var btn_pc = '<button id="addNewEmployee" class="btn btn-default" type="button" title="Add new">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>';
        btn_pc += '&nbsp;&nbsp;<button id="refreshAll" class="btn btn-default" type="button" title="Reload">&nbsp;<i class="fa fa-refresh"></i>&nbsp;</button>';

        $('.dataTables_filter','#employee_dataTables_wrapper').prepend(btn_pc);
        $("#refreshAll").click(function(){ set.dataTable.ajax.reload(); });

        $('#addNewEmployee').click(function(){

            $.confirm({
                title: 'Add New',
                content: '<center>'+set.btn_spin_html+'</center>',
                columnClass: 'col-md-12',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore:function(){

                    var $this = this;
                    $.get( bu+"admin/employees/get_new_employee_html", function( data ) {
                        $this.setContent('<br>'+data);
                        $('select[multiple]').multiselect();
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

                            if(!$this.$content.find('#mobile').val()){
                                $this.$content.find('#mobile').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#age').val()){
                                $this.$content.find('#age').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#gender').val()){
                                $this.$content.find('#gender').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#address').val()){
                                $this.$content.find('#address').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#city').val()){
                                $this.$content.find('#city').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#state').val()){
                                $this.$content.find('#state').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#experience').val()){
                                $this.$content.find('#experience').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#approx_salary').val()){
                                $this.$content.find('#approx_salary').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#bio').val()){
                                $this.$content.find('#bio').addClass('error-text').parent().find('.spantext').show();
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

                            $.post( bu+"admin/employees/addEmployee", $('#new_emp_form').serialize(), function( data ) {

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

        $("#employee_dataTables").on("click",".editEmployee",function(){
            
            _this = $(this);
            var rowData = set.dataTable.row(_this.parent().parent()).data();
            
            $.confirm({
                title: 'Edit Employee',
                content: '<center>'+set.btn_spin_html+'</center>',
                columnClass: 'col-md-12',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore: function(){

                    var $this = this;
                    $.get( bu+"admin/employees/get_new_employee_html/"+rowData.id, function( data ) {
                        $this.setContent('<br>'+data);
                        $('select[multiple]').multiselect();
                    });
                },
                onOpen: function(){

                    var $this = this;
                    $this.$content.find('#first_name').val(rowData.first_name);
                    $this.$content.find('#last_name').val(rowData.last_name);
                    $this.$content.find('#mobile').val(rowData.mobile).attr('disabled','true');
                    $this.$content.find('#email').val(rowData.email);
                    $this.$content.find('#age').val(rowData.age);
                    $this.$content.find('#gender').val(rowData.gender);
                    $this.$content.find('#address').val(rowData.address);
                    $this.$content.find('#pin_code').val(rowData.pin_code);
                    $this.$content.find('#city').val(rowData.city);
                    $this.$content.find('#state').val(rowData.state);
                    $this.$content.find('#education').val(rowData.education);
                    $this.$content.find('#experience').val(rowData.experience);
                    $this.$content.find('#approx_salary').val(rowData.approx_salary);
                    $this.$content.find('#salary').val(rowData.salary);
                    $this.$content.find('#language_know').val(rowData.language_know);
                    $this.$content.find('#can_drive').val(rowData.can_drive);
                    $this.$content.find('#driving_licence_no').val(rowData.driving_licence_no);
                    $this.$content.find('#have_vehicle').val(rowData.have_vehicle);
                    $this.$content.find('#work_timing').val(rowData.work_timing);
                    $this.$content.find('#location').val(rowData.location);
                    $this.$content.find('#bio').val(rowData.bio);

                },
                buttons: 
                {
                    submit: 
                    {
                        text: 'update',
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

                            if(!$this.$content.find('#mobile').val()){
                                $this.$content.find('#mobile').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#age').val()){
                                $this.$content.find('#age').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#gender').val()){
                                $this.$content.find('#gender').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#address').val()){
                                $this.$content.find('#address').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#city').val()){
                                $this.$content.find('#city').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#state').val()){
                                $this.$content.find('#state').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#experience').val()){
                                $this.$content.find('#experience').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#approx_salary').val()){
                                $this.$content.find('#approx_salary').addClass('error-text').parent().find('.spantext').show();
                                error = true;
                            }

                            if(!$this.$content.find('#bio').val()){
                                $this.$content.find('#bio').addClass('error-text').parent().find('.spantext').show();
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

                            $.post( bu+"admin/employees/updateEmployee", $('#new_emp_form').serialize(), function( data ) {

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

        $("#employee_dataTables").on("click",".changeEmployeeStatus",function(){
            
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
                content: '<br><center>Are you sure you want to '+Rstatus+' this employee ?</center><br>',
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

                            $.post( bu+"admin/employees/changeEmployeeStatus", {'emp_id':rowData.id,'status':Rstatus}, function( data ) {

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

        $("#employee_dataTables").on("click",".openEmpDocs",function(){
            
            _this = $(this);
            var rowData = set.dataTable.row(_this.parent().parent()).data();
            
            set.docData = $.confirm({
                title: rowData.first_name+' '+rowData.first_name+' Documents',
                content: '<center>'+set.btn_spin_html+'</center>',
                columnClass: 'col-md-8 col-md-offset-2',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore: function(){

                    var $this = this;
                    $.post( bu+"admin/employees/getEmployeeDocs", {emp_id: rowData.id}, function( data ) {
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
                                
                            var param = new FormData($("#uploadNewDocument")[0]);

                            if(!$this.$content.find('#document_name').val()){
                                $this.$content.find('#document_name').addClass('error-text').parent().find('.spantext').show();
                                return false;
                            }

                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.ajax({
                                url: bu+"admin/employees/uploadNewDocument",
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

        function approveFuction(){

            $('.approveDoc').click(function(){
                
                _this = $(this);
                var doc_id = _this.attr('data-doc');
                var emp_id = _this.attr('data-id');
                
                if(!doc_id || !emp_id){
                    return false;
                }

                ajaxRequestOn = true;

                _this.html(set.btn_spin_html);
                set.docData.buttons.submit.disable();
                set.docData.buttons.cancel.disable();
                
                $.post( bu+"admin/employees/updateEmployeeDocStatus", {emp:emp_id,doc:doc_id}, function( data ) {

                    set.docData.buttons.submit.enable();
                    set.docData.buttons.cancel.enable();
                    
                    ajaxRequestOn = false;
                    
                    data = $.parseJSON(data);
                    if(data.status == '202'){

                        $.notify(data.msg);
                    }

                    if(data.status == '200'){

                        _this.attr('data-doc','');
                        _this.attr('data-id','');
                        _this.removeClass('approveDoc');
                        _this.html('<i class="fa fa-check text-suucess"></i>');
                        $.notify(data.msg, "success");
                    }
                });
            })
        }
    });
</script>