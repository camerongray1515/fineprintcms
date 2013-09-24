<script type="text/javascript" src="<?php echo application_base_url('assets/js/fineprint.files.js'); ?>"></script>

<h3>Files</h3>

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

<form action="<?php echo admin_url("files/file_actions") ?>" method="POST">
    <div class="top-buttons">
        <a href="#" class="btn btn-success"><i class="icon-white icon-plus"></i> Upload File</a>
        <a href="<?php echo admin_url("files/create_directory/?path=$path"); ?>" class="btn btn-success"><i class="icon-white icon-folder-open"></i> Create Directory</a>
        <button type="submit" class="btn btn-info" name="copy"><i class="icon-white icon-resize-horizontal"></i> Copy</button>
        <button type="submit" class="btn btn-info" name="move"><i class="icon-white icon-arrow-right"></i> Move</button>
        <button type="submit" class="btn btn-info" name="rename"><i class="icon-white icon-edit"></i> Rename</button>
        <button type="submit" class="btn btn-danger" name="delete"><i class="icon-white icon-trash"></i> Delete</button>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th style="width: 10px;"></th>
            <th style="width: 25px;"></th>
            <th>Name</th>
            <th>Size</th>
            <th>Last Modified</th>
        </tr>
        </thead>

        <tbody>
            <?php if (!$top_level): ?>
                <tr>
                    <td></td>
                    <td><i class="icon-files-parent"></i></td>
                    <td><a href="?path=<?php echo $parent_directory; ?>">Parent Directory</a></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif; ?>

            <?php foreach($directories as $directory): ?>
                <tr>
                    <td class="checkbox-container"><input type="checkbox" name="item[]" value="<?php echo $directory->name; ?>" /></td>
                    <td><i class="icon-files-directory"></i></td>
                    <td><a href="?path=<?php echo $directory->path; ?>"><?php echo $directory->name; ?></a></td>
                    <td><?php echo $directory->size; ?></td>
                    <td><?php echo $directory->last_modified; ?></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach($files as $file): ?>
                <tr>
                    <td class="checkbox-container"><input type="checkbox" name="item[]" value="<?php echo $file->name; ?>" /></td>
                    <td><i class="icon-files-file"></i></td>
                    <td><?php echo $file->name; ?></td>
                    <td><?php echo $file->size; ?></td>
                    <td><?php echo $file->last_modified; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="path" value="<?php echo $path; ?>"/>
</form>