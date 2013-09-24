<h3>Rename</h3>

<form action="<?php echo admin_url("files/do_rename/no_ajax"); ?>" method="POST">
    <div class="form-horizontal">
        <?php foreach ($items as $i=>$item): ?>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend input-append">
                        <span class="add-on" style="width: 200px; text-align: right;"><?php echo $item; ?></span>
                        <span class="add-on"><i class="icon-arrow-right"></i></span>
                        <input type="text" style="width: 200px;" name="new_name[<?php echo $i; ?>]" value="<?php echo $item; ?>" />
                        <input type="hidden" name="original_name[<?php echo $i; ?>]" value="<?php echo $item; ?>" />
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <input type="hidden" name="path" value="<?php echo $path; ?>" />

        <div class="form-actions">
            <button type="submit" class="btn btn-success"><i class="icon-white icon-edit"></i> Rename</button>
            <a href="<?php echo admin_url("files?path=$path"); ?>" class="btn">Cancel</a>
        </div>
    </div>
</form>