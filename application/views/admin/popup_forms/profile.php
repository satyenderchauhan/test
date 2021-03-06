<style type="text/css">
    .ms-options{
        min-height:0px !important;
    }
</style>
<form role="form" id="profileForm">
    <div class="col-md-12">
        <input type="hidden" class="form-control" name="user_id" value="<?=$user_id;?>" />
        <div class="col-md-4">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?=$profile->first_name?>" placeholder="Enter first name" id="first_name" />
                <span class="spantext">Invalid First Name</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" value="<?=$profile->last_name?>" placeholder="Enter last name" id="last_name" />
                <span class="spantext">Invalid Last Name</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" class="form-control" name="mobile" maxlength="10" value="<?=$profile->username?>" placeholder="Enter mobile number" id="mobile" disabled="true"/>
                <span class="spantext">Invalid Mobile Number</span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" class="form-control" name="email" value="<?=$profile->email?>" placeholder="Enter email address" id="email" />
                <span class="spantext">Invalid Email Address</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" minlength="6" placeholder="Enter password" id="password" disabled="true"/>
                <span class="spantext">Password should not less than 6 charactor </span>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group" style="margin-top: 30px;">
                <a class="resetPassword" style="cursor: pointer;">Reset Password ?</a>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $('.resetPassword').click(function(){
        if($(document).find('#password').attr('disabled') == 'disabled'){
            $('#password').removeAttr('disabled','true');
        }else{
            $('#password').attr('disabled','true');        
            $('#password').removeClass('error-text').parent().find('.spantext').hide();        
        }
    });
</script>