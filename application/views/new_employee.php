<style type="text/css">
    .ms-options{
        min-height:0px !important;
    }
</style>
<form role="form" id="new_emp_form">
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
                <input type="text" class="form-control" name="mobile" placeholder="Enter mobile number" id="mobile" />
                <span class="spantext">Invalid Mobile Number</span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>Email Address</label>
                <input type="text" class="form-control" name="email" placeholder="Enter email address" id="email" />
                <span class="spantext">Invalid Email Address</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Age</label>
                <input type="text" class="form-control" name="age" placeholder="Enter age" id="age" />
                <span class="spantext">Invalid Age</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Gender</label>
                <input type="text" class="form-control" name="gender" placeholder="Enter gender" id="gender" />
                <span class="spantext">Invalid Gender</span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-8">
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" placeholder="Enter address" id="address" />
                <span class="spantext">Invalid Address</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>City</label>
                <input type="text" class="form-control" name="city" placeholder="Enter city" id="city" />
                <span class="spantext">Invalid City</span>
            </div>
        </div>        
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>State</label>
                <input type="text" class="form-control" name="state" placeholder="Enter state" id="state" />
                <span class="spantext">Invalid State</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Education</label>
                <input type="text" class="form-control" name="education" placeholder="Enter education" id="education" />
                <span class="spantext">Invalid Education</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Experience</label>
                <input type="text" class="form-control" name="experience" placeholder="Enter experience" id="experience" />
                <span class="spantext">Invalid Experience</span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>Approx Salary</label>
                <input type="text" class="form-control" name="approx_salary" placeholder="Enter approx salary" id="approx_salary" />
                <span class="spantext">Invalid Approx Salary</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Salary</label>
                <input type="text" class="form-control" name="salary" placeholder="Enter salary" id="salary" />
                <span class="spantext">Invalid Salary</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>language know</label>
                <input type="text" class="form-control" name="language_know" placeholder="Enter language know" id="language_know" />
                <span class="spantext">Invalid language know</span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label>Can Drive</label>
                <select class="form-control" id="can_drive" name="can_drive" placehoder="Select can drive">
                    <option value="No">No</option>
                    <option value="2 Wheeler">2 Wheeler</option>
                    <option value="3 Wheeler">3 Wheeler</option>
                    <option value="4 Wheeler">4 Wheeler</option>
                    <option value="All">All</option>
                </select>
                <span class="spantext">Invalid Can Drive</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Driving Licence No</label>
                <input type="text" class="form-control" name="driving_licence_no" placeholder="Enter driving licence no" id="driving_licence_no" />
                <span class="spantext">Invalid Driving Licence No</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Have Vehicle</label>
                <select class="form-control" id="have_vehicle" name="have_vehicle" placehoder="Select vehicle">
                    <option value="No">No</option>
                    <option value="2 Wheeler">2 Wheeler</option>
                    <option value="3 Wheeler">3 Wheeler</option>
                    <option value="4 Wheeler">4 Wheeler</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-12 field_wrapper">
        <div class="col-md-4">
            <div class="form-group">
                <label>Work Timing</label>
                <select class="form-control" id="work_timing" name="work_timing" placehoder="Select work timing">
                    <option value="Part Time">Part Time (1 -4 hr)</option>
                    <option value="Full Time">Full Time (5 -12 hr)</option>
                </select>
                <span class="spantext">Invalid Work Timing</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Work Can Do</label>
                <select name="langOpt[]" multiple id="langOpt" id="occupations" name="occupations[]" placehoder="Select occupation">
                    <?php foreach ($all_occupation as $key => $value) { ?>
                        <option value="<?=$value->occupation;?>"><?=$value->occupation;?></option>                        
                    <?php }?>
                </select>
                <span class="spantext">Invalid Occupation</span>
            </div>
        </div>
        <div class="col-md-4">
            <label>Can Work Here</label>
            <div class="input-group">
                <input type="text" class="form-control" id="location" name="location[]"/>
                <span class="form-group input-group-btn">
                    <button class="btn btn-default add_button" type="button"><i class="fa fa-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="col-md-8">
            <div class="form-group">
                <label>Bio</label>
                <textarea type="text" class="form-control" name="bio" placeholder="Enter bio" id="bio" /></textarea>
                <span class="spantext">Invalid Bio</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Profile Pic</label>
                <input type="file" name="profile_pic" id="profile_pic" />
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">

    var x = 1; //Initial field counter is 1
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var clearFixHTML = '<div class="clearfix"></div>';
    var fieldHTML = '<div class="col-md-4"> <label>Can Work Here</label> <div class="input-group"> <input type="text" class="form-control" id="location" name="location[]"/> <span class="form-group input-group-btn"> <button class="btn btn-default remove_button" type="button"><i class="fa fa-minus"></i></button> </span> </div> </div>'; //New input field html
    
    $(addButton).click(function(){
        
        if(x < maxField){
            if(x == 1){
                $(wrapper).append(clearFixHTML); //Add field html
            }
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        alert('here');
        $(this).parent('span').parent('div').parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
// });
</script>