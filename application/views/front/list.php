<?php $this->view('front/templates/header'); ?>

<link href="<?php echo base_url();?>assets/css/front/bootstrap.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/css/front/custom.css" rel="stylesheet" />
<style type="text/css">
  .intro-text {
    padding-top: 150px !important;
    padding-bottom: 100px !important;
}
</style>
<!-- Header -->
  <header class="masthead">
    <div class="container">
      <div class="intro-text">
        <div class="intro-lead-in"><?php echo ucfirst(str_replace('_', ' ', $_GET['occupation']));?></div>
      </div>
    </div>
  </header>

<section class="page-section" id="services">
    <div class="container">
        <div class="row">
            <?php foreach ($all_emp as $key => $value) { ?>
                
                <?php 
                    $src = 'assets/img/find_user.png';
                    if($value->profile_pic){
                        $src = 'data:image/png;base64,'.base64_encode($value->profile_pic);
                    }
                ?>

                <!-- Team member -->
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="image-flip" ontouchstart="this.classList.toggle('hover');">
                        <div class="mainflip">
                            <div class="frontside">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p><img class=" img-fluid" src="<?php echo $src; ?>" alt="card image"></p>
                                        <h4 class="card-title"><?php echo $value->first_name.' '.$value->last_name; ?></h4>
                                        <p class="card-text"><?php echo $value->occupations;?></p>
                                        <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="backside">
                                <div class="card">
                                    <div class="card-body text-center mt-4">
                                        <h4 class="card-title"><?php echo $value->first_name.' '.$value->last_name; ?></h4>
                                        <p class="card-text">
                                            Gender : <?php echo $value->gender;?><br>
                                            Age : <?php echo $value->age;?><br>
                                            Experience : <?php echo $value->experience;?><br>
                                            Approx Salary : <?php echo $value->approx_salary;?>
                                        </p>
                                        <p>
                                            <a class="text-xs-center"> Check Availablity </a>
                                            <br>
                                            <a class="text-xs-center readMore" data-id="<?php echo $value->id;?>" style="cursor: pointer;"> Read More </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./Team member -->
            
            <?php }?>

        </div>
    </div>
</section>



<?php $this->view('front/templates/footer'); ?>
<script type="text/javascript">
  
  $('.readMore').click(function(){
            
    _this = $(this);
    var u_id = _this.attr('data-id');

    $.confirm({
        title: 'Processing',
        content: '<center>Get data.....</center>',
        columnClass: 'col-md-12',
        draggable: true,
        dragWindowBorder: false,
        onOpenBefore:function(){

            var $this = this;
            $.post( bu+"search/get_user_data_html", {u_id : u_id}, function( data ) {

              $this.setTitle('sdffsaf');
              $this.setContent('<br>'+data);
            });
        },
        buttons: 
        {
          cancel: 
          {
            text: 'Close',
            btnClass: 'btn-default'
          }
        }
    });
  });
</script>