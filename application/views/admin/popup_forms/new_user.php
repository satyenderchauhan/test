<style type="text/css">
    .ms-options{
        min-height:0px !important;
    }
</style>
<form role="form" id="newUserForm">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" placeholder="Enter first name" id="first_name" />
                <span class="spantext">Invalid First Name</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" placeholder="Enter last name" id="last_name" />
                <span class="spantext">Invalid Last Name</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" class="form-control" name="mobile" maxlength="10" placeholder="Enter mobile number" id="mobile" />
                <span class="spantext">Invalid Mobile Number</span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email address" id="email" />
                <span class="spantext">Invalid Email Address</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" minlength="6" placeholder="Enter password" id="password" />
                <span class="spantext">Password should not less than 6 charactor </span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="Enter confirm password" id="confirm_password" />
                <span class="spantext">Password & confirm password must be same</span>
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