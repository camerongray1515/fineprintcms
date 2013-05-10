<script type="text/javascript" src="<?php echo base_url('assets/js/tinymce/tinymce.min.js'); ?>"></script>

<script type="text/javascript">
	tinymce.init({
		selector: "textarea.textarea-content",
		theme: "modern",
		plugins: ["advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table contextmenu directionality",
			"emoticons template paste"
		],
		toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		toolbar2: "print preview media | forecolor backcolor"
	});
	
	var content_editor = {
		get_content: function()
		{
			// Could really be cleaned up as seems pretty fragile
			return $(tinymce.get('content').contentDocument).children().children('#tinymce').html();
		}
	}
</script>