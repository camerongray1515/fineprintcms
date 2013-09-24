<h3>Modules</h3>

<div id="alert-container">
    <?php
    if ($message = $this->session->flashdata('message'))
    {
        $type = 'success';
        $title = 'Success!';
        if (!$this->session->flashdata('success'))
        {
            $type = 'error';
            $title = 'Error!';
        }

        echo alert_html($title, $message, $type);
    }
    ?>
</div>

<?php if (count($modules)): ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Version</th>
            <th>Developer</th>
            <th style="width: 245px;">Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($modules as $module): ?>
            <tr data-module-identifier="<?php echo $module->identifier; ?>">
                <td><?php echo $module->name; ?></td>
                <td><?php echo $module->version; ?></td>
                <td><?php echo $module->developer; ?></td>
                <td>
                    <a class="btn btn-danger delete-button" href="<?php echo admin_url("modules/uninstall/{$module->identifier}"); ?>" data-layout-id="<?php echo $module->identifier; ?>"><i class="icon-trash icon-white"></i> Uninstall</a>
                    <a class="btn btn-info" href="<?php echo admin_url("modules/module/{$module->identifier}"); ?>"><i class="icon-arrow-right icon-white"></i> Enter Module</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info"><strong>Info:</strong> You have not yet installed any modules.  Upload a module to the server then press "Scan for newly installed modules" above.</div>
<?php endif; ?>