<hr>

<?php if($result): ?>
    <script type="text/javascript">
        alert('<?php echo $result['message']; ?>');
    </script>
<?php endif; ?>

<form action="<?php echo module_frontend_url('comments/post') ?>" method="POST">
    <p>Name: <input type="text" name="name"/></p>
    <p>Email: <input type="text" name="email"/></p>
    <p>Comment:</p>
    <textarea name="comment" id="comment" cols="60" rows="10"></textarea>

    <br>

    <input type="hidden" name="page-alias" value="<?php echo $page_alias; ?>" />
    <input type="hidden" name="redirect-to" value="<?php echo current_url(); ?>" />

    <input type="submit" value="Post Comment"/>
</form>