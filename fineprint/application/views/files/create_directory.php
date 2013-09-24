<h3>Create Directory</h3>

<form action="<?php echo admin_url('files/do_create_directory/no_ajax'); ?>" method="POST" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="directory_name">Directory Name:</label>
        <div class="controls">
            <div class="input-prepend">
                <span class="add-on"><?php echo $path; ?></span>
                <input type="text" id="directory_name" name="directory_name" placeholder="e.g. Photos">
            </div>
        </div>
    </div>

    <input type="hidden" name="path" value="<?php echo $path; ?>"/>

    <div class="form-actions">
        <button type="submit" class="btn btn-success">Create Directory</button>
        <a href="<?php echo admin_url("files?path=$path"); ?>" class="btn">Cancel</a>
    </div>
</form>