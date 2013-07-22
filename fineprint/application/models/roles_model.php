<?php
class Roles_model extends CI_Model {
    function get_all_roles()
    {
        $database_response = $this->db->get('roles');

        $all_roles = $database_response->result();

        return $all_roles;
    }

    function get_role_translations()
    {
        $roles = $this->get_all_roles();

        $translations = array();

        foreach ($roles as $role) {
            $translations[$role->id] = $role;
        }

        return $translations;
    }

    function get_all_controllers()
    {
        $database_response = $this->db->get('controllers');

        $all_controllers = $database_response->result();

        return $all_controllers;
    }

    function get_all_roles_and_allowed_controllers()
    {
        $sql = "SELECT
                        `r`.`id` AS `role_id`,
                        `c`.`id` AS `controller_id`
                FROM
                        `roles` AS `r`,
                        `allowed_to_access` AS `a`,
                        `controllers` AS `c`
                WHERE
                        `r`.`id` = `a`.`role` AND
                        `c`.`id` = `a`.`controller`
                ORDER BY `r`.`id`;";

        $database_response = $this->db->query($sql);

        $all_roles_and_allowed_controllers = $database_response->result();

        return $all_roles_and_allowed_controllers;
    }

    function get_allowed_controllers($role_id)
    {
        $sql = "SELECT
                        `c`.*
                FROM
                        `roles` AS `r`,
                        `allowed_to_access` AS `a`,
                        `controllers` AS `c`
                WHERE
                        `r`.`id` = `a`.`role` AND
                        `c`.`id` = `a`.`controller` AND
                        `r`.`id` = ?";

        $database_response = $this->db->query($sql, array($role_id));

        $all_roles_and_allowed_controllers = $database_response->result();

        return $all_roles_and_allowed_controllers;
    }

    function get_data_for_table()
    {
        // Get all roles and allowed controllers
        $roles_and_controller_permissions = $this->get_all_roles_and_allowed_controllers();
        $controllers = $this->get_all_controllers();

        // New array for proper permissions
        $table_data = array();

        foreach ($roles_and_controller_permissions as $role_and_permissions)
        {
            $table_data[$role_and_permissions->role_id][$role_and_permissions->controller_id] = TRUE;
        }

        // Now go through again and for any controllers that aren't already there (allowed) set them to false!
        foreach ($table_data as $role=>$permissions)
        {
            foreach ($controllers as $controller)
            {
                $table_data[$role][$controller->id] = (isset($table_data[$role][$controller->id]))? TRUE : FALSE;
            }
        }

        // Simple sort to get entries in order of most permission first
        $sorted_table_data = array();
        $temp_table_data = $table_data;
        do
        {
            $max_permissions = 0;
            $max_permission_key = '';

            foreach ($temp_table_data as $key=>$permissions)
            {
                // Count allowed controllers
                $allowed_controllers = 0;

                foreach ($permissions as $permission)
                {
                    if ($permission)
                    {
                        $allowed_controllers++;
                    }
                }

                if ($allowed_controllers > $max_permissions)
                {

                    $max_permissions = $allowed_controllers;
                    $max_permission_key = $key;
                }
            }

            $sorted_table_data[$max_permission_key] = $table_data[$max_permission_key];
            unset ($temp_table_data[$max_permission_key]);
        }
        while (!empty($temp_table_data));

        return $sorted_table_data;
    }

    function add_role($name, $allowed_controllers)
    {
        if (empty($name))
        {
            throw new Exception('You must supply a name for this role!', 1);
        }

        if (empty($allowed_controllers))
        {
            throw new Exception('You must select at least one permission for this role!', 1);
        }

        // Insert role
        $this->db->insert('roles', array('name' => $name));

        $role_id = $this->db->insert_id();

        $allowed_controllers_data = array();
        foreach($allowed_controllers as $allowed_controller)
        {
            $current_entry = array(
                'role'          => $role_id,
                'controller'    => $allowed_controller
            );

            array_push($allowed_controllers_data, $current_entry);
        }

        $this->db->insert_batch('allowed_to_access', $allowed_controllers_data);

        return TRUE;
    }

    function get_basic_role($role_id)
    {
        $this->db->where('id', $role_id);
        $database_response = $this->db->get('roles');

        return $database_response->row();
    }

    function get_full_role($role_id)
    {
        $allowed_controllers = $this->get_allowed_controllers($role_id);
        $all_controllers = $this->get_all_controllers();
        $basic_role = $this->get_basic_role($role_id);

        $allowed_controller_ids = array();

        foreach ($allowed_controllers as $controller)
        {
            array_push($allowed_controller_ids, $controller->id);
        }

        $full_role = new stdClass();
        $full_role->id = $basic_role->id;
        $full_role->name = $basic_role->name;
        $full_role->controllers = array();

        foreach ($all_controllers as $controller)
        {
            $has_permission = TRUE;
            if (array_search($controller->id, $allowed_controller_ids) === FALSE)
            {
                $has_permission = FALSE;
            }

            $full_role->controllers[$controller->id] = new stdClass();
            $full_role->controllers[$controller->id]->has_permission = $has_permission;
            $full_role->controllers[$controller->id]->internal_name = $controller->internal_name;
            $full_role->controllers[$controller->id]->display_name = $controller->display_name;
            $full_role->controllers[$controller->id]->description = $controller->description;
        }

        return $full_role;
    }

    function edit_role($role_id, $name, $allowed_controllers)
    {
        if (empty($name))
        {
            throw new Exception('You must supply a name for this role!', 1);
        }

        if (empty($allowed_controllers))
        {
            throw new Exception('You must select at least one permission for this role!', 1);
        }

        // Insert role
        $this->db->update('roles', array('name' => $name), array('id' => $role_id));

        // Delete all current controller permissions
        $this->db->where('role', $role_id);
        $this->db->delete('allowed_to_access');

        $allowed_controllers_data = array();
        foreach($allowed_controllers as $allowed_controller)
        {
            $current_entry = array(
                'role'          => $role_id,
                'controller'    => $allowed_controller
            );

            array_push($allowed_controllers_data, $current_entry);
        }

        $this->db->insert_batch('allowed_to_access', $allowed_controllers_data);

        return TRUE;
    }

    function delete_role($role_id)
    {
        // First check that no users are assigned to this role, if so, refuse to remove
        $this->db->where('role', $role_id);
        $database_response = $this->db->get('users');
        $users_with_role = $database_response->result();

        if ($users_with_role)
        {
            $error_string = "The following users are currently assigned to this role, either remove the users or assign them to a different role, then try deleing the role again:<ul>";
            foreach ($users_with_role as $user)
            {
                $error_string .= "<li>{$user->first_name} {$user->last_name} ({$user->username})</li>";
            }
            $error_string .= "</ul>";

            throw new Exception($error_string, 1);
        }

        // Revoke all permission from this role
        $this->db->where('role', $role_id);
        $this->db->delete('allowed_to_access');

        // Remove the role
        $this->db->where('id', $role_id);
        $this->db->delete('roles');

        if (!$this->db->affected_rows())
        {
            throw new Exception('Role could not be deleted, please try again, if this error persists, please contact your administrator.', 1);
        }

        return TRUE;
    }
}