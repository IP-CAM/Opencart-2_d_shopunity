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
				<h1><?php echo $extension['name']; ?></h1>
				<div class="row">
					<div class="col-md-4">
					
						<?php if($extension['store_extension'] || empty($extension['prices'])){ ?>
							<?php if(!$extension['installed']){?>
					                <a class="btn btn-info btn-block" href="<?php echo $extension['install']; ?>">Install</a>
					           		<a class="btn btn-info btn-block" href="<?php echo $extension['suspend']; ?>">Suspend</a>
					    	<?php }else{ ?>
					                <a class="btn btn-success btn-block" href="<?php echo $extension['update']; ?>">Update</a>
					                <a class="btn btn-success btn-block" href="<?php echo $extension['download']; ?>" > Download</a>
					                <a class="btn btn-danger btn-block" href="<?php echo $extension['uninstall']; ?>">Uninstall</a>
					            
					    	<?php } ?>
				        <?php }else{ ?>
				        	<?php if(!empty($extension['price'])){ ?>
							<div class="purchase">
								<select class="form-control">
									<?php foreach($extension['prices'] as $price){ ?>
									<option value="<?php echo $price['extension_recurring_price_id']; ?>"><?php echo $price['recurring_price']; ?></option>
									<?php } ?>
								</select>
								<div class="well">
									<a class="btn btn-primary btn-block" href="<?php echo $purchase; ?>">Purchase</a>
								</div>
							</div>
					        <?php } ?>
				        <?php } ?>
					</div>
					<div class="col-md-8">
						<div class="image">
							<img src="<?php echo $extension['processed_images'][2]['url']; ?>" class="img-responsive"/>
						</div>
						<div class="description"><?php echo html_entity_decode($extension['description']); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).on('click', '.purchase .btn', function(){
		var href = $(this).attr('href');
			href += '&extension_recurring_price_id='+$(this).parents('.purchase').find('select').val();
			//console.log(href);
			location.href = href;
		return false;

	});

</script>
<?php echo $footer; ?>