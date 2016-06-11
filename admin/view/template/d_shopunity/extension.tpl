<?php
/*
 *	location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="form-inline pull-right">
				<?php if($stores){ ?>
				<select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
					<?php foreach($stores as $store){ ?>
					<?php if($store['store_id'] == $store_id){ ?>
					<option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
					<?php }else{ ?>
					<option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
				<?php } ?>
				<button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
					<li class="active"><a href="<?php echo $href_extension; ?>" >
						<span class="fa fa-puzzle-piece"></span> 
						<?php echo $tab_extension; ?>
					</a></li>
					<li><a href="<?php echo $href_market; ?>" >
						<span class="fa fa-flask"></span> 
						<?php echo $tab_market; ?>
					</a></li>
					<li><a href="<?php echo $href_account; ?>" >
						<span class="fa fa-cog"></span> 
						<?php echo $tab_account; ?>
					</a></li>
					<li><a href="<?php echo $href_backup; ?>" >
						<span class="fa fa-undo"></span> 
						<?php echo $tab_backup; ?>
					</a></li>
				</ul>

			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<?php echo $profile; ?>
						
					</div>
					<div class="col-md-9">
						<div class="ibox">
							<div class="ibox-title">
								<h4>Purchased modules.</h4>
								<p>These modules have been purchased. You can use them only for this webshop.</p>
							</div>
						</div>

						<?php if($store_extensions){ ?>
						<div class="row">
							<?php foreach($store_extensions as $extension) { ?>
								<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb.tpl'); ?>
							<?php } ?>
						</div>
						<?php }else{ ?>
							<div class="bs-callout bs-callout-info">No store modules to display</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="bs-callout bs-callout-info">
							<h4>Expired licenses.</h4>
							<p>These modules do not have a license, or their lisence has been expired. </p>
						</div>
					</div>
					<div class="col-md-9">		 
						<?php if($local_extensions){ ?>
						<div class="row">
							<?php foreach($local_extensions as $extension) { ?>
								<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb.tpl'); ?>
							<?php } ?>
						</div>
						<?php }else{ ?>
							<div class="bs-callout bs-callout-info">No local modules to display</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="bs-callout bs-callout-info">
							<h4>Unknown Modules.</h4>
							<p>These modules are not regestered with the shopunity network.</p>
						</div>
					</div>
					<div class="col-md-9">
						<?php if($unregestered_extensions){ ?>
						<div class="row">
							<?php foreach($unregestered_extensions as $extension) { ?>
								<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb.tpl'); ?>
							<?php } ?>
						</div>
						<?php }else{ ?>
							<div class="bs-callout bs-callout-info">No local modules to display</div>
						<?php } ?>
					<!-- 	<pre>

							
						<?php print_r($store_extensions );?>
					</pre>  
					<pre>
						<?php print_r($local_extensions );?>
					</pre>   -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
		d_shopunity.init({ 
			'purchase_url': '<?php echo $purchase_url; ?>'
		});
</script>
<?php echo $footer; ?>