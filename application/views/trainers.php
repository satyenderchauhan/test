<?php $this->view('templates/header'); ?>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>All Trainers</h2>   
                    </div>
                </div>
                 <hr />
               
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="trainers_dataTables">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Id</th>
                                            <th>Gym Name</th>
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
        var dataTable = $('#trainers_dataTables').DataTable({
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
                "url": bu+"trainers/trainerList",
                "type": "POST",
                "data": function (d) {}
            },
            "columns":  [
                {"className":'details-control',"orderable":false,"visible":false,"data":'',"defaultContent": ''},
                { "data": "id","visible":false},
                { "data": "gym_name",
                    render: function( data, type, row)
                    {
                        return '<a>'+data+'</a>';
                    }
                },
                { "data": "first_name"},
                { "data": "last_name"},
                { "data": "number"},
                { "data": "email",},
                { "data": "created_dt"},
                { "data": "address"},
                { "data": "",
                    render: function ( data, type, row ) 
                    {
                        if(data == 1){
                            return "<a style='margin-right:10px' class='btn btn-xs btn-danger activateGym' data-id="+row.id+" title='Activate'><i class=\"fa fa-times\"></i></a>";
                        }else{
                            return "<a style='margin-right:10px' class='btn btn-xs btn-success deactivateGym' data-id="+row.id+" title='Deactivate'><i class=\"fa fa-check\"></i></a>";
                        }
                    }
                },
            ],
            responsive: true
        });
    });
</script>