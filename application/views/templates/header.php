<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="assets/css/jquery-confirm.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script>
        bu ="<?php echo base_url();?>";
    </script>
    <style type="text/css">
        .error-text{
            border-color: red;
        }
        .spantext{
            color: red;
            display: none;
        }
        .dataTables_filter input{
            height: 35px!important;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><?php echo ucfirst($this->session->userdata('name')); ?></a> 
            </div>
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"><a href="secure/logout" class="btn btn-danger square-btn-adjust">Logout</a> 
            </div>
        </nav>   
       <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/find_user.png" class="user-image img-responsive"/>
                    </li>
                    <li>
                        <a class="<?php echo (@$this->active=='dashboard')?'active-menu':'';?>"  href="<?php echo base_url();?>dashboard"><i class="fa fa-dashboard fa-2x"></i> Dashboard</a>
                    </li>
                    <li>
                        <a class="<?php echo (@$this->active=='owners')?'active-menu':'';?>" href="<?php echo base_url();?>owners"><i class="fa fa-desktop fa-2x"></i> Gym Owners</a>
                    </li>
                    <li>
                        <a class="<?php echo (@$this->active=='employees')?'active-menu':'';?>" href="<?php echo base_url();?>employees"><i class="fa fa-desktop fa-2x"></i> Gym Employees</a>
                    </li>
                    <li>
                        <a class="<?php echo (@$this->active=='gyms')?'active-menu':'';?>" href="<?php echo base_url();?>gyms"><i class="fa fa-desktop fa-2x"></i> Gyms</a>
                    </li>
                    <li>
                        <a class="<?php echo (@$this->active=='trainers')?'active-menu':'';?>" href="<?php echo base_url();?>trainers"><i class="fa fa-qrcode fa-2x"></i> Trainers</a>
                    </li>
                    <li>
                        <a class="<?php echo (@$this->active=='users')?'active-menu':'';?>" href="<?php echo base_url();?>users"><i class="fa fa-users fa-2x"></i> Users</a>
                    </li>   
                </ul>
            </div>
        </nav>  