<div class="extension-thumb extension-thumb-row"  id="extension_<?php echo $extension['codename']; ?>">
	<div class="row">
		<div class="col-md-1">
			<a href="<?php echo $extension['url']; ?>">
			   <img class="img-responsive" src="<?php echo $extension['processed_images'][1]['url']; ?>" alt="" />
			</a>
		</div>
		<div class="col-md-3">
			<span class="name"><?php echo $extension['name']; ?></span>
			<span class="label label-info"><?php echo $extension['current_version']; ?></span> 
			<?php if($extension['downloadable'] && $extension['tester_status_id']){?>
				<div class="alert alert-warning m-t"><?php echo ${'text_tester_status_'.$extension['tester_status_id']}; ?></div>
			<?php } ?>
			<?php if($extension['update_available']){?>
				<div class="alert alert-info m-t"><?php echo $text_new_version_available; ?> <?php echo $extension['version']; ?></div>
			<?php } ?>
			
		</div>
		<div class="col-md-1">
			<!-- <div class="rating">
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    	</div> -->
	    	
		</div>
		<div class="col-md-3">
			<?php if($extension['downloadable'] && $extension['tester_comment']){ ?>
			<div class="alert alert-info"><?php echo $extension['tester_comment']; ?></div>
			<?php } ?>
		</div>
		<div class="col-md-4">
			<div class="pull-right ">
				<div class="form-inline">
	            
	        	<?php if($extension['updatable'] && $extension['installed']){ ?>
	        	<a class="btn btn-success show-loading update-extension" data-href="<?php echo $extension['update']; ?>&theme=extension_thumb_row"  data-toggle="tooltip" data-original-title="Update"><span class="fa fa-refresh"></span></a>
	        	<?php } ?>
	        	
		       
				<?php if($extension['purchasable'] ){ ?>
		        <div class="purchase-extension btn-group">
				
						<?php if(!empty($extension['price'])){ ?>
			             <select class="form-control">
							<?php foreach($extension['prices'] as $price){ ?>
							<option value="<?php echo $price['extension_recurring_price_id']; ?>"><?php echo $price['recurring_price_format']; ?> / <?php echo $price['recurring_duration']; ?> days</option>
							<?php } ?>
						</select>
			          
			            <?php } ?>
			           
			            <a class="btn btn-primary pull-right" data-extension-id="<?php echo $extension['extension_id'];?>">Buy</a>
			   
				</div>
				<?php } ?>

				<a class="btn btn-info show-extension-json" data-href="<?php echo $extension['json']; ?>" data-toggle="tooltip" data-original-title="mbooth.json"><span class="fa fa-code"></span></a>
				<?php if($extension['admin']){ ?>
					<a class="btn btn-info show-loading" href="<?php echo $extension['admin']; ?>"  data-toggle="tooltip" data-original-title="Admin"><span class="fa fa-pencil"></span></a>
				<?php } ?>
				<?php if($extension['activate']){ ?>
					<a class="btn btn-success activate-extension" data-href="<?php echo $extension['activate']; ?>"  data-toggle="tooltip" data-original-title="Activate"><span class="fa fa-power-off "></span></a>
				<?php } ?>
				<?php if($extension['deactivate']){ ?>
					<a class="btn btn-danger deactivate-extension" data-href="<?php echo $extension['deactivate']; ?>"  data-toggle="tooltip" data-original-title="Deactivate"><span class="fa fa-power-off "></span></a>
				<?php } ?>
				<?php if($extension['downloadable'] ){ ?>
	        		<a class="btn btn-default download-extension" data-href="<?php echo $extension['download']; ?>"  data-toggle="tooltip" data-original-title="Download"><span class="fa fa-download"></span></a>
		        <?php } ?>

				<?php if($extension['installable'] && !$extension['installed']){ ?>
	            	<a class="btn btn-info show-loading install-extension" data-href="<?php echo $extension['install']; ?>&theme=extension_thumb_row"  data-toggle="tooltip" data-original-title="Install"><span class="fa fa-magic"></span></a>
	            <?php } ?>

	            <?php if($extension['installed']){ ?>
					<a class="btn btn-danger show-loading delete-extension" data-href="<?php echo $extension['uninstall']; ?>&theme=extension_thumb_row"  data-toggle="tooltip" data-original-title="Delete"><span class="fa fa-trash-o"></span></a>	
	            <?php } ?>

				<?php if($extension['suspendable'] && !$extension['installed']){ ?>
	            <a class="btn btn-danger show-loading suspend-extension" data-href="<?php echo $extension['suspend']; ?>" data-toggle="tooltip" data-original-title="Suspend"><span class="fa fa-ban"></span></a>
	        	<?php } ?>

	        	<?php if($extension['submittable']){ ?>
	                <a class="btn btn-warning show-loading submit-extension" data-href="<?php echo $extension['submit']; ?>" data-toggle="tooltip" data-original-title="Submit"><span class="fa fa-cloud-upload"></span></a>
		        <?php } ?>
				</div>
		        <?php if($extension['testable']){ ?>
		        <hr/>
		        <div class="form-inline">
	                <a class="btn btn-primary show-loading approve-extension" data-href="<?php echo $extension['approve']; ?>" data-toggle="tooltip" data-original-title="Approve"><span class="fa fa-thumbs-up"></span></a>
	                <a class="btn btn-danger show-loading disapprove-extension" data-href="<?php echo $extension['disapprove']; ?>" data-toggle="tooltip" data-original-title="Disaprove"><span class="fa fa-thumbs-down"></span></a>
	                <a class="btn btn-warning show-loading test-extension" data-href="<?php echo $extension['test']; ?>&theme=extension_thumb_row" data-toggle="tooltip" data-original-title="Test"><span class="fa fa-cloud-download"></span></a>
		        </div>
		        <?php } ?>
		       	
	        </div>
		</div>
	</div>
	<hr/>
</div>