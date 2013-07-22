<h4>Dashboard Settings</h4>

<form class="form-horizontal" action="<?php echo admin_url('settings/dashboard_settings_do_save/no_ajax'); ?>" method="POST" id="dashboard-form">
    <div class="control-group">
        <label class="control-label" style="margin-top: 13px;">Dashboard Mode</label>
        <div class="controls">
            <label class="radio"><input type="radio" name="dashboard-type" value="static" <?php echo ($mode == 'static')? 'checked': ''; ?>/> Static Content</label>
            <label class="radio"><input type="radio" name="dashboard-type" value="iframe" <?php echo ($mode == 'iframe')? 'checked': ''; ?>/> Content from an external URL displayed in an iFrame</label>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="frame-url">Frame URL</label>
        <div class="controls">
            <input type="text" id="frame-url" name="frame-url" placeholder="The URL to display if your Dashboard is set to display an iFrame" class="input-xxlarge" value="<?php echo $frame_url; ?>">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="content">Content</label>
        <div class="controls">
            <textarea id="content" name="content" class="textarea-content"><?php echo $static_content; ?></textarea>
        </div>
    </div>

    <div class="form-actions">
        <?php echo top_buttons_button('save', 'Save Settings', 'success', 'hdd'); ?>
    </div>
</form>