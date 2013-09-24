<?php
class Files extends FP_Controller {
    function index()
    {
        $this->load->helper('directory');

        $path = $this->input->get('path');
        $full_path = FILE_DIR . "/$path/";
        do
        {
            $full_path = str_replace('//', '/', $full_path);
        }
        while(strpos($full_path, '//') !== FALSE);

        do
        {
            $full_path = str_replace('..', '', $full_path);
        }
        while(strpos($full_path, '..') !== FALSE);

        $directory_listing = directory_map($full_path, 1);

        $directories = array();
        $files = array();

        foreach ($directory_listing as $entry)
        {
            if (is_dir($full_path . $entry))
            {
                $entry_object = new stdClass();
                $entry_object->name = $entry;
                $entry_object->path = str_replace(FILE_DIR, '', $full_path . $entry);
                $entry_object->size = format_bytes(dirsize($full_path . $entry));
                $entry_object->last_modified = date('r', filemtime($full_path . $entry . '/.'));

                array_push($directories, $entry_object);
            }
            else
            {
                $entry_object = new stdClass();
                $entry_object->name = $entry;
                $entry_object->path = str_replace(FILE_DIR, '', $full_path . $entry);
                $entry_object->size = format_bytes(filesize($full_path . $entry));
                $entry_object->last_modified = date('r', filemtime($full_path . $entry));

                array_push($files, $entry_object);
            }
        }

        // Are we at the top level?
        $top_level = trim(str_replace(FILE_DIR, '', str_replace('/', '', $full_path)));
        $top_level = empty($top_level);

        $data = array(
            'top_level'         => $top_level,
            'directories'       => $directories,
            'files'             => $files,
            'parent_directory'  => dirname(str_replace(FILE_DIR, '', $full_path)),
            'path'              => $path
        );

        // die(print_r($data['files'], TRUE));

        $this->load->view('common/header', array('title' => 'Files'));
        $this->load->view('files/index', $data);
        $this->load->view('common/footer');
    }

    function create_directory()
    {
        $path = $this->input->get('path');
        $path = "$path/";

        do
        {
            $path = str_replace('//', '/', $path);
        }
        while(strpos($path, '//') !== FALSE);

        do
        {
            $path = str_replace('..', '', $path);
        }
        while(strpos($path, '..') !== FALSE);

        $data['path'] = $path;

        $this->load->view('common/header', array('title' => 'Create Directory'));
        $this->load->view('files/create_directory', $data);
        $this->load->view('common/footer');
    }

    function do_create_directory($no_ajax = FALSE)
    {
        $path = $this->input->post('path');
        $directory_name = $this->input->post('directory_name');

        // Clean directory name
        $directory_name = preg_replace("/[^A-Za-z0-9 ]/", '', $directory_name);

        $full_path = FILE_DIR . "/$path/$directory_name";
        do
        {
            $full_path = str_replace('//', '/', $full_path);
        }
        while(strpos($full_path, '//') !== FALSE);

        do
        {
            $full_path = str_replace('..', '', $full_path);
        }
        while(strpos($full_path, '..') !== FALSE);

        $message = 'Directory has been created successfully!';
        $success = mkdir($full_path);

        if (!$success)
        {
            $message = 'An error occurred when creating the directory, does it already exist?';
        }

        $data = array(
            'result' => array(
                'success'	=> $success,
                'message'	=> $message
            ),
            'redirect_to' => admin_url("files?path=$path"),
            'ajax' => !$no_ajax
        );

        $this->load->view('action_result', $data);
    }

    function file_actions()
    {
        $path = $this->input->post('path');

        if (!$this->input->post('item'))
        {
            $data = array(
                'result' => array(
                    'success'	=> FALSE,
                    'message'	=> 'You must select at least one file'
                ),
                'redirect_to' => admin_url("files?path=$path"),
                'ajax' => FALSE
            );

            $this->load->view('action_result', $data);
        }

        if (isset($_POST['copy']))
        {
            die('copy');
        }
        else if (isset($_POST['move']))
        {
            die('move');
        }
        else if (isset($_POST['rename']))
        {
            $data['items'] = $this->input->post('item');
            $data['path'] = $path;

            $this->load->view('common/header', array('title' => 'Rename'));
            $this->load->view('files/rename', $data);
            $this->load->view('common/footer');
        }
        else
        {
            show_404();
        }
    }

    function do_rename($no_ajax = FALSE)
    {
        $this->load->model('file_model');

        $new_names = $this->input->post('new_name');
        $original_names = $this->input->post('original_name');
        $path = $this->input->post('path');

        $success = TRUE;
        $message = (count($original_names) == 1)? 'File has': 'Files have';
        $message .= ' been renamed successfully!';

        try
        {
            $this->file_model->bulk_rename($new_names, $original_names, $path);
        }
        catch (Exception $e)
        {
            $success = FALSE;
            $message = $e->getMessage();
        }

        $data = array(
            'result' => array(
                'success'	=> $success,
                'message'	=> $message
            ),
            'redirect_to' => admin_url("files?path=$path"),
            'ajax' => !$no_ajax
        );

        $this->load->view('action_result', $data);
    }
}