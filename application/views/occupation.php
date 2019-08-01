<?php $this->view('templates/header'); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-10">
                        <h2>All Occupations</h2>                        
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
                                <table class="table table-striped table-bordered table-hover" id="occupation_dataTables">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Id</th>
                                            <th>Occupation</th>
                                            <th>Create By</th>
                                            <th>Created Dt</th>
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

        var occupationHtml = '<form role="form">'+
                '<div class="col-md-12">'+
                    '<div class="form-group">'+
                        '<input type="text" class="form-control" name="occupation" placeholder="Enter new occupation" id="occupation" />'+
                        '<span class="spantext">Invalid Occupation</span>'+
                    '</div>'+
                '</div>'+
            '</form>';

        set.dataTable = $('#occupation_dataTables').DataTable({
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
                "url": bu+"occupations/occupationList",
                "type": "POST",
                "data": function (d) {}
            },
            "columns":  [
                {"className":'details-control',"orderable":false,"visible":false,"data":'',"defaultContent": ''},
                { "data": "id","visible":false},
                { "data": "occupation"},
                { "data": "first_name",
                    render: function ( data, type, row )
                    {
                        return data + ' ' + row.last_name;
                    }
                },
                { "data": "created_at"},
                { "data": "id",
                    render: function ( data, type, row ) 
                    {
                        var msg = "<a style='margin-right:10px' class='btn btn-xs btn-primary editOccupation' data-id="+row.id+">Edit</a>";
                            msg += "<a style='margin-right:10px' class='btn btn-xs btn-danger removeOccupation' data-id="+row.id+" title='Remove'><i class=\"fa fa-times\"></i></a>";
                        return msg;
                    }
                },
            ],
            responsive: true
        });

        var btn_pc = '<button id="addNewOccupation" class="btn btn-default" type="button" title="Add new">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>';
        btn_pc += '&nbsp;&nbsp;<button id="refreshAll" class="btn btn-default" type="button" title="Reload">&nbsp;<i class="fa fa-refresh"></i>&nbsp;</button>';

        $('.dataTables_filter','#occupation_dataTables_wrapper').prepend(btn_pc);
        $("#refreshAll").click(function(){ set.dataTable.ajax.reload(); });

        $('#addNewOccupation').click(function(){

            $.confirm({
                title: 'Add New',
                content: occupationHtml,
                columnClass: 'col-md-4 col-md-offset-4',
                draggable: true,
                dragWindowBorder: false,
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
                            param.occupation = $this.$content.find('#occupation').val();
                            
                            if(!param.occupation){
                                $this.$content.find('#occupation').addClass('error-text').parent().find('.spantext').show();
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

                            $.post( bu+"occupations/addOccupation", param, function( data ) {

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

        $("#occupation_dataTables").on("click",".editOccupation",function(){
            
            _this = $(this);
            var rowData = set.dataTable.row(_this.parent().parent()).data();
            
            $.confirm({
                title: 'Edit Occupation',
                content: occupationHtml,
                columnClass: 'col-md-4 col-md-offset-4',
                draggable: true,
                dragWindowBorder: false,
                onOpenBefore: function(){

                    this.$content.find('#occupation').val(rowData.occupation);
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
                            var param = {};
                            param.occupation_id = rowData.id;
                            param.occupation = $this.$content.find('#occupation').val();
                            
                            if(!param.occupation){
                                $this.$content.find('#occupation').addClass('error-text').parent().find('.spantext').show();
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

                            $.post( bu+"occupations/updateOccupation", param, function( data ) {

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

        $("#occupation_dataTables").on("click",".removeOccupation",function(){
            
            _this = $(this);
            var occupationId = _this.attr('data-id');

            $.confirm({
                title: 'Confirm!',
                content: '<br><center>Are you sure you want to Remove this Occupation ?</center><br>',
                columnClass: 'col-md-4 col-md-offset-4',
                draggable: true,
                dragWindowBorder: false,
                buttons: 
                {
                    submit: 
                    {
                        text: 'Remove',
                        btnClass: 'btn-danger',
                        action: function () {
                            
                            var $this = this;
                            
                            ajaxRequestOn = true;
                            $this.setTitle("Processing...");
                            $this.buttons.submit.disable();
                            $this.buttons.submit.setText(set.btn_spin_html);
                            $this.buttons.cancel.disable();

                            $.post( bu+"occupations/removeOccupation", {'occupationId':occupationId}, function( data ) {

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