<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.roles.js') ?>"></script>

<h4>Edit Role</h4>

<form action="<?php echo admin_url('roles/do_edit/no_ajax'); ?>" method="POST" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="name">Name</label>
        <div class="controls">
            <input type="text" name="name" id="name" class="input-xxlarge" placeholder="e.g. Administrator" value="<?php echo $role->name; ?>" />
        </div>
    </div>

    <div class="control-group">
        <div class="controls well" id="permissions-container">
            <h5>Permissions</h5>

            <?php foreach ($role->controllers as $controller_id => $controller): ?>
                <label class="checkbox">
                    <input type="checkbox" class="controller-checkbox" name="controllers[]" value="<?php echo $controller_id; ?>" <?php if ($controller->has_permission) { echo 'checked'; } ?> />
                    <?php echo $controller->description; ?>
                </label>
            <?php endforeach; ?>

            <div class="btn-toolbar js-only">
                <div class="btn-group">
                    <button class="btn" id="check-all">Check All</button>
                    <button class="btn" id="uncheck-all">Uncheck All</button>
                    <button class="btn" id="swap-checked-and-unchecked">Swap Checked & Unchecked</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="role-id" name="role-id" value="<?php echo $role->id; ?>" />

    <div class="form-actions">
        <?php echo top_buttons_button('edit', 'Save Role', 'success', 'hdd'); ?>
        <?php echo top_buttons_link('roles', 'Cancel', 'default'); ?>
    </div>
</form>