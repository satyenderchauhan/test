<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link href="<?php echo base_url();?>assets/css/admin/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/css/font-awesome.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/js/admin/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/css/admin/custom.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/css/jquery-confirm.css" rel="stylesheet" />
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
    <?php
        $this->load->helper('common');
        $menu = getMenu();        
    ?>
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
                
            </div>
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
                <a class="btn btn-primary square-btn-adjust openProfile" style="cursor: pointer;"><?php echo ucfirst($this->session->userdata('name')); ?></a> 
                <a href="secure/logout" class="btn btn-danger square-btn-adjust">Logout</a> 
            </div>
        </nav>   
       <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <?php 
                            $src = 'assets/img/find_user.png';
                            if($this->session->userdata('profile_pic')){
                                $src = 'data:image/png;base64,'.base64_encode($this->session->userdata('profile_pic'));
                            }
                        ?>

                            <img src="<?php echo $src; ?>" class="user-image img-responsive"/>
                    </li>

                    <?php foreach ($menu as $key => $value) { ?>
                        
                        <li>
                            <a class="<?php echo (@$this->active==$value->url)?'active-menu':'';?>"  href="<?php echo base_url().$value->url;?>"><i class="<?=$value->icon?> fa-2x"></i> <?=$value->name?></a>
                        </li>

                    <?php }?>
                    
                </ul>
            </div>
        </nav>  