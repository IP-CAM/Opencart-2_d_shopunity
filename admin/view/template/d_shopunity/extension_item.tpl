<?php
/*
 *	location: admin/view
 */
?>
<?php echo $content_top; ?>
<div class="ibox">
	<div class="ibox-content">
		<h1><?php echo $extension['name']; ?></h1>
		<p><?php echo $extension['description_short']; ?></p>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<div class="ibox">
			<img src="<?php echo $extension['processed_images'][2]['url']; ?>" class="img-responsive"/>
		</div>
		<div class="ibox">
			<div class="ibox-title">
				User Actions
			</div>
			<div class="ibox-content">
				
				<?php if($extension['view']){ ?>
					<a class="btn btn-info btn-block show-loading" href="<?php echo $extension['view']; ?>"  data-toggle="tooltip" data-original-title="Admin"><span class="fa fa-cog"></span> Admin</a>
				<?php } ?>

				<?php if($extension['updatable'] && $extension['installed']){ ?>
	        	<a class="btn btn-success btn-block show-loading update-extension" data-href="<?php echo $extension['update']; ?>"  data-toggle="tooltip" data-original-title="Update"><span class="fa fa-refresh"></span> Update</a>
	        	<?php } ?>
	        	
		       
				<?php if($extension['purchasable'] ){ ?>
		        <div class="purchase-extension">
					<div class="row ">
						<?php if(!empty($extension['price'])){ ?>
			            <div class="col-xs-12 col-md-6">
			                <select class="form-control">
								<?php foreach($extension['prices'] as $price){ ?>
								<option value="<?php echo $price['extension_recurring_price_id']; ?>"><?php echo $price['recurring_price']; ?></option>
								<?php } ?>
							</select>
			            </div>
			            <?php } ?>
			            <div class="col-xs-12 col-md-6">
			                <a class="btn btn-primary pull-right" data-extension-id="<?php echo $extension['extension_id'];?>">Buy</a>
			            </div>
			        </div>
				</div>
				<?php } ?>

				
				

				<?php if($extension['installable'] && !$extension['installed']){ ?>
	            	<a class="btn btn-info btn-block show-loading install-extension" data-href="<?php echo $extension['install']; ?>"  data-toggle="tooltip" data-original-title="Install"><span class="fa fa-magic"></span> Install</a>
	            <?php } ?>

	            <?php if($extension['installed']){ ?>
					<a class="btn btn-danger btn-block show-loading delete-extension" data-href="<?php echo $extension['uninstall']; ?>"  data-toggle="tooltip" data-original-title="Delete"><span class="fa fa-trash-o"></span> Delete</a>	
	            <?php } ?>

				<?php if($extension['suspendable'] && !$extension['installed']){ ?>
	            <a class="btn btn-danger btn-block show-loading suspend-extension" data-href="<?php echo $extension['suspend']; ?>" data-toggle="tooltip" data-original-title="Suspend"><span class="fa fa-ban"></span> Suspend</a>
	        	<?php } ?>

	        	
			</div>
		</div>
		<div class="ibox">
			<div class="ibox-title">
				Developer & Tester Actions
			</div>
			<div class="ibox-content">

				<a class="btn btn-info btn-block show-extension-json" data-href="<?php echo $extension['json']; ?>" data-toggle="tooltip" data-original-title="mbooth.json"><span class="fa fa-code"></span> Json</a>

	        	<?php if($extension['downloadable'] ){ ?>
	        		<a class="btn btn-default btn-block download-extension" data-href="<?php echo $extension['download']; ?>"  data-toggle="tooltip" data-original-title="Download"><span class="fa fa-download"></span> Download</a>
		        <?php } ?>

	        	<?php if($extension['submittable']){ ?>
	                <a class="btn btn-warning btn-block show-loading submit-extension" data-href="<?php echo $extension['submit']; ?>" data-toggle="tooltip" data-original-title="Submit"><span class="fa fa-cloud-upload"></span> Submit</a>
		        <?php } ?>

		        <?php if($extension['testable']){ ?>
	                <a class="btn btn-primary btn-block show-loading approve-extension" data-href="<?php echo $extension['approve']; ?>" data-toggle="tooltip" data-original-title="Approve"><span class="fa fa-thumbs-up"></span> Approve</a>
	                <a class="btn btn-danger btn-block show-loading disapprove-extension" data-href="<?php echo $extension['disapprove']; ?>" data-toggle="tooltip" data-original-title="Disaprove"><span class="fa fa-thumbs-down"></span> Disaprove</a>
		        <?php } ?>
	
				<?php if($extension['testable']){ ?>
	                <a class="btn btn-warning btn-block show-loading test-extension" data-href="<?php echo $extension['test']; ?>" data-toggle="tooltip" data-original-title="Test"><span class="fa fa-cloud-download"></span> Test</a>
		        <?php } ?>
			</div>
    	</div>
        <?php echo $developer; ?>
	</div>
	<div class="col-md-9">
		<div class="ibox">
			<div class="ibox-content description"><?php echo html_entity_decode($extension['description']); ?></div>
		</div>
		<!-- <pre>	
			<?php print_r($extension );?>
		</pre>  -->
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
<?php echo $content_bottom; ?>