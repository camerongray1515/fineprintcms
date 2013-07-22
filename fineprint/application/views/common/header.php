<?php $this->load->view('common/top_html', array('title' => $title)); ?>

<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<?php echo top_nav_item('dashboard', 'Dashboard', 'dashboard', 'home'); ?>
					<?php echo top_nav_item('pages', 'Pages', 'pages', 'file'); ?>
					<?php echo top_nav_item('blocks', 'Blocks', 'blocks', 'th-large'); ?>
					<?php echo top_nav_item('snippets', 'Snippets', 'snippets', 'th'); ?>
					<?php echo top_nav_item('layouts', 'Layouts', 'layouts', 'align-justify'); ?>
					<?php echo top_nav_item('files', 'Files', 'files', 'folder-open'); ?>
					<?php echo top_nav_item('modules', 'Modules', 'modules', 'wrench'); ?>
					<?php echo top_nav_item('users', 'Users', 'users', 'user'); ?>
					<?php echo top_nav_item('settings', 'Settings', 'settings', 'cog'); ?>
				</ul>
				<div class="pull-right">
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, <?php echo $this->login_model->get_logged_in_user('first_name'); ?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a class="non-link" href="#"><strong>Previous Visit Information:</strong></a></li>
								<li><a class="non-link" href="#">Last logged in: <?php echo date(TABLE_DATE_FORMAT, strtotime($this->login_model->get_logged_in_user('last_logged_in'))); ?></a></li>
								<li><a class="non-link" href="#">Last logged in from: <?php echo $this->login_model->get_logged_in_user('last_ip'); ?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo admin_url('profile/edit'); ?>"><i class="icon-cog"></i> Account Settings</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo admin_url('login/logout'); ?>"><i class="icon-off"></i> Logout</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container" id="page-container">
