<div class="extension-thumb-row">
	<div class="row">
		<div class="col-md-1">
			<a href="<?php echo $extension['url']; ?>">
			   <img class="img-responsive" src="<?php echo $extension['processed_images'][1]['url']; ?>" alt="" />
			</a>
		</div>
		<div class="col-md-3">
			<span class="name"><?php echo $extension['name']; ?></span>
			<?php echo $extension['version']; ?>
			<?php if($extension['downloadable'] && $extension['tester_status_id']){?>
				<div class="alert alert-warning m-t"><?php echo ${'text_tester_status_'.$extension['tester_status_id']}; ?></div>
			<?php } ?>
		</div>
		<div class="col-md-2">
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
		<div class="col-md-3">
			<div class="pull-right">
				
	            
	        	<?php if($extension['updatable'] && $extension['installed']){ ?>
	        	<a class="btn btn-success show-loading update-extension" data-href="<?php echo $extension['update']; ?>"  data-toggle="tooltip" data-original-title="Update"><span class="fa fa-refresh"></span></a>
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

				<a class="btn btn-info show-extension-json" data-href="<?php echo $extension['json']; ?>" data-toggle="tooltip" data-original-title="mbooth.json"><span class="fa fa-code"></span></a>
				<?php if($extension['view']){ ?>
					<a class="btn btn-info show-loading" href="<?php echo $extension['view']; ?>"  data-toggle="tooltip" data-original-title="Admin"><span class="fa fa-cog"></span></a>
				<?php } ?>
				<?php if($extension['downloadable'] ){ ?>
	        		<a class="btn btn-default download-extension" data-href="<?php echo $extension['download']; ?>"  data-toggle="tooltip" data-original-title="Download"><span class="fa fa-download"></span></a>
		        <?php } ?>

				<?php if($extension['installable'] && !$extension['installed']){ ?>
	            	<a class="btn btn-info show-loading install-extension" data-href="<?php echo $extension['install']; ?>"  data-toggle="tooltip" data-original-title="Install"><span class="fa fa-magic"></span></a>
	            <?php } ?>

	            <?php if($extension['installed']){ ?>
					<a class="btn btn-danger show-loading delete-extension" data-href="<?php echo $extension['uninstall']; ?>"  data-toggle="tooltip" data-original-title="Delete"><span class="fa fa-trash-o"></span></a>	
	            <?php } ?>

				<?php if($extension['suspendable'] && !$extension['installed']){ ?>
	            <a class="btn btn-danger show-loading suspend-extension" data-href="<?php echo $extension['suspend']; ?>" data-toggle="tooltip" data-original-title="Suspend"><span class="fa fa-ban"></span></a>
	        	<?php } ?>

	        	<?php if($extension['submittable']){ ?>
	                <a class="btn btn-warning show-loading submit-extension" data-href="<?php echo $extension['submit']; ?>" data-toggle="tooltip" data-original-title="Submit"><span class="fa fa-cloud-upload"></span></a>
		        <?php } ?>

		        <?php if($extension['testable']){ ?>
	                <a class="btn btn-primary show-loading approve-extension" data-href="<?php echo $extension['approve']; ?>" data-toggle="tooltip" data-original-title="Approve"><span class="fa fa-thumbs-up"></span></a>
	                <a class="btn btn-danger show-loading disapprove-extension" data-href="<?php echo $extension['disapprove']; ?>" data-toggle="tooltip" data-original-title="Disaprove"><span class="fa fa-thumbs-down"></span></a>
		        <?php } ?>
	
				<?php if($extension['testable']){ ?>
	                <a class="btn btn-warning show-loading test-extension" data-href="<?php echo $extension['test']; ?>" data-toggle="tooltip" data-original-title="Test"><span class="fa fa-cloud-download"></span></a>
		        <?php } ?>
		       
	        </div>
		</div>
	</div>
	<hr/>
</div>