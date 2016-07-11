<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="form-inline pull-right">
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if (!empty($error)) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (!empty($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="<?php echo $logout; ?>" class="btn btn-default pull-right"><?php echo $button_logout; ?></a>
				<ul  class="nav nav-pills">
					<li <?php if ($route == 'd_shopunity/extension') { ?>class="active" <?php } ?>><a href="<?php echo $href_extension; ?>" >
						<span class="fa fa-puzzle-piece"></span> 
						<?php echo $tab_extension; ?>
					</a></li>
					<li <?php if ($route == 'd_shopunity/market') { ?>class="active" <?php } ?>><a href="<?php echo $href_market; ?>" >
						<span class="fa fa-flask"></span> 
						<?php echo $tab_market; ?>
					</a></li>
					<li <?php if ($route == 'd_shopunity/order' || $route == 'd_shopunity/invoice' || $route == 'd_shopunity/transaction'  ) { ?>class="active" <?php } ?>><a href="<?php echo $href_billing; ?>" >
						<span class="fa fa-money"></span> 
						<?php echo $tab_billing; ?>
					</a></li>
					<!-- <li><a href="<?php echo $href_backup; ?>" >
						<span class="fa fa-undo"></span> 
						<?php echo $tab_backup; ?>
					</a></li> -->
					<li <?php if ($route == 'd_shopunity/setting') { ?>class="active" <?php } ?>><a href="<?php echo $href_setting; ?>" >
						<span class="fa fa-cog"></span> 
						<?php echo $tab_setting; ?>
					</a></li>
				</ul>
			</div>
			<div class="panel-body">