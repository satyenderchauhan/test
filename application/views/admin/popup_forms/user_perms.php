<style type="text/css">
    .ms-options{
        min-height:0px !important;
    }
</style>
<form role="form" id="userPermForm">
    <div class="col-md-12">
        <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?=$user_id;?>" />
        
        <?php foreach ($all_perms as $key => $value) { ?>
           
            <div class="col-md-4">
                <div class="form-group">
                    <label>
                        <input style="margin-right: 10px;" type="checkbox" name="perm[]" id="perms" value="<?=$value->id?>" 
                        <?php if(in_array($value->id, $selected_perms)){ echo 'checked="true"';} ?> /><?php echo $value->name;?>
                    </label>
                </div>
            </div>

        <?php } ?>
    </div>
</form>