<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

   <script src="assets/js/jquery-1.10.2.js"></script>
   <script>
        bu ="<?php echo base_url();?>";
    </script>
    <style type="text/css">
      .text-error{
        color: red;
        margin-top: -15px;
        margin-bottom: 10px;
        font-size: 10px;
      }
    </style>
</head>
<body>
    <div class="container">
        <div class="row text-center ">
            <div class="col-md-12">
                <br /><br />
                <h2> Admin : Login</h2>
               
                <h5>( Login yourself to get access )</h5>
                 <br />
            </div>
        </div>
        <div class="row ">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>   Enter Login Credientials </strong>  
                    </div>
                    <div class="panel-body">
                        <form role="form" name="login_form" id="login_form" method="post">
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                                <input type="text" class="form-control" placeholder="Your Username " required="true" name="username" id="username" maxlength="10" data-error-text="Enter Mobile Number"/>
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                <input type="password" id="password" name="password" class="form-control"  placeholder="Your Password" required="true" />
                            </div>
                            <button class="btn btn-primary" type="submit">Login Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script> 
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- <script src="assets/js/jquery.metisMenu.js"></script> -->
    <!-- <script src="assets/js/custom.js"></script> -->
    <script src="assets/js/secure.js"></script>
   
</body>
</html>
