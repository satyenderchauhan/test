<style type="text/css">
    .ms-options{
        min-height:0px !important;
    }
</style>

<div class="col-md-12 d-flex">
    <div class="col-md-2">
        <?php 
            $src = 'assets/img/find_user.png';
            if($profile_pic->profile_pic){
                $src = 'data:image/png;base64,'.base64_encode($profile_pic->profile_pic);
            }
        ?>
        <img class=" img-fluid" src="<?php echo $src; ?>" alt="card image">
    </div>
    <div class="col-md-10">
        <p><?=$emp_data->bio?></p>
        <p>Can Work As : <?=$selected_occupation?></p>
        <p>Can be Contacting On : <?=$selected_occupation?></p>
    </div>
</div>

<div class="col-md-12 d-flex" style="padding-top: 20px;">
    <div class="col-md-2"></div>
    <div class="col-md-2 col-xs-5">
        <strong>Address</strong>
    </div>
    <div class="col-md-3 col-xs-5">
        fa dsf dsagf sdf g sdfg
    </div>
    <div class="col-md-2 col-xs-5">
        <strong>Age</strong>
    </div>
    <div class="col-md-3 col-xs-5">
        56
    </div>
</div>
<div class="col-md-12 d-flex">
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <strong>Education</strong>
    </div>
    <div class="col-md-3">
        fa dsf dsagf sdf g sdfg
    </div>
    <div class="col-md-2">
        <strong>Gender</strong>
    </div>
    <div class="col-md-3">
        56
    </div>
</div>
<div class="col-md-12 d-flex">
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <strong>Experience</strong>
    </div>
    <div class="col-md-3">
        fa dsf dsagf sdf g sdfg
    </div>
    <div class="col-md-2">
        <strong>Language Know</strong>
    </div>
    <div class="col-md-3">
        56
    </div>
</div>
<div class="col-md-12 d-flex">
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <strong>Approx Salary</strong>
    </div>
    <div class="col-md-3">
        fa dsf dsagf sdf g sdfg
    </div>
    <div class="col-md-2">
        <strong>Gender</strong>
    </div>
    <div class="col-md-3">
        56
    </div>
</div>
<div class="col-md-12 d-flex">
    <div class="col-md-2"></div>
    <div class="col-md-2">
        <strong>Salary</strong>
    </div>
    <div class="col-md-3">
        fa dsf dsagf sdf g sdfg
    </div>
    <div class="col-md-2">
        <strong>Gender</strong>
    </div>
    <div class="col-md-3">
        56
    </div>
</div>