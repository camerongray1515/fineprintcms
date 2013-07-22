<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.roles.js') ?>"></script>

<h4>Roles & Permissions</h4>

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

<p>In this section you can manage roles to control what areas of Fine Print different users can access</p>

<div class="top-buttons">
    <?php echo top_buttons_link('roles/add', 'Add Role', 'success', 'plus'); ?>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Role Name</th>
            <?php foreach ($controllers as $controller): ?>
                <th class="permission" title="<?php echo $controller->description; ?>"><?php echo $controller->display_name; ?></th>
            <?php endforeach; ?>
            <th class="actions">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($roles_table as $role=>$permissions): ?>
            <tr data-role-id="<?php echo $role; ?>">
                <td><?php echo $roles[$role]->name; ?></td>
                <?php foreach ($controllers as $controller): ?>
                    <td>
                        <?php if ($permissions[$controller->id]): ?>
                            <i class="icon-large-tick"></i>
                        <?php else: ?>
                            <i class="icon-large-cross"></i>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <td class="actions">
                    <a class="btn btn-danger delete-role" href="<?php echo admin_url("roles/delete/{$role}") ?>" data-role-id="<?php echo $role; ?>">
                        <i class="icon-trash icon-white"></i> Delete
                    </a>

                    <a class="btn btn-info" href="<?php echo admin_url("roles/edit/{$role}") ?>">
                        <i class="icon-edit icon-white"></i> Edit
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>