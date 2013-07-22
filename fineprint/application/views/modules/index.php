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

<div class="top-buttons">
    <a class="btn" href="<?php echo admin_url('modules/scan_new');  ?>"><i class="icon-refresh"></i> Scan for newly installed modules</a>
</div>

<?php if (count($modules)): ?>
    <p>There should be a table here!</p>
<?php else: ?>
    <div class="alert alert-info"><strong>Info:</strong> You have not yet installed any modules.  Upload a module to the server then press "Scan for newly installed modules" above.</div>
<?php endif; ?>