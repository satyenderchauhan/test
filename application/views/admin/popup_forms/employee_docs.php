<style type="text/css">
    .ms-options{
        min-height:0px !important;
    }
</style>
<form role="form" id="uploadNewDocument" enctype="multipart/form-data">

    <input type="hidden" class="form-control" name="emp_id" value="<?=$emp_id;?>" />
    <?php if(count($selected_docs) > 0){?>
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="">
                    <label>Document</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <label>Action</label>
                </div>
            </div>
        </div>
    <?php }?>

    <?php if(count($selected_docs) > 0){
        foreach ($selected_docs as $key => $value) { ?>
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <?=$value->document_name?> &nbsp;&nbsp;<a href="<?php echo base_url().$value->url; ?>" target="_blank">View</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if($value->is_approved){ echo '<i class="fa fa-check text-success"></i>';}else{ echo '<a  style="text-decoration: none; cursor:pointer;" class="approveDoc" data-id="'.$emp_id.'" data-doc="'.$value->id.'"> Approve</a>'; }?>
                    </div>
                </div>
            </div>
        <?php }
    }?>

    <div class="col-md-12" style="margin-top: 20px;">
        <div class="col-md-6">
            <div class="form-group">
                <label>Select Document</label>
                <select class="form-control" id="document_name" name="document_name">
                    <option selected="" disabled="">Select</option>
                    <option value="profile_pic">Selfie</option>
                    <option value="Aadhaar Card">Aadhaar Card</option>
                    <option value="Pan Card">Pan Card</option>
                    <option value="Voter Card">Voter Card</option>
                    <option value="Ration Card">Ration Card</option>
                    <option value="Driving Licence">Driving Licence</option>
                    <option value="Police Verification">Police Verification</option>
                </select>
                <span class="spantext">Invalid Document Name</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Upload Document</label>
                <input type="file" name="document" id="document" />
                <span class="spantext">Invalid document</span>
            </div>
        </div>
    </div>

</form>