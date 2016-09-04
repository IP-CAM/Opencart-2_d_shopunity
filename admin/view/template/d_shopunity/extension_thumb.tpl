<div class="extension-thumb" id="extension_id_<?php echo $extension['extension_id']; ?>">
	<div class="ibox">
		<!-- <?php if(!empty($extension['description_short'])) {?>
        <p class="absolute-description">
            <?php echo $extension['description_short']; ?>
        </p>
        <?php } ?> -->
        <div class="loading">
        	<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
			<span class="sr-only">Loading...</span>
        </div>
		<a href="<?php echo $extension['url']; ?>">
		   <img class="img-responsive" src="<?php echo $extension['processed_images'][1]['url']; ?>" alt="" />
		</a>
		<div class="ibox-content clearfix">
		    <div class="caption">
		        <h4 class="name">
		            <?php echo $extension['name']; ?>
		        </h4>
		        <p class="info">
		        	<span class="version label label-info">v<?php echo $extension['version']; ?></span>
					<?php if($extension['installed'] && $extension['store_extension'] && !$extension['store_extension']['status']) { ?>
		        	<span class="version label label-danger" data-toggle="tooltip" data-original-title="This extension is installed, yet you do not have an order or the invoice is not paid.">Unpaid</span>
		        	<?php } ?>
		        </p>

				<div class="pull-right">
					<?php if($extension['view']){ ?>
						<a class="btn btn-info btn-sm show-loading" href="<?php echo $extension['view']; ?>"  data-toggle="tooltip" data-original-title="Admin"><span class="fa fa-cog"></span></a>
					<?php } ?>
					
	                <?php if($extension['installable'] && !$extension['installed']){ ?>
		            	<a class="btn btn-info btn-sm show-loading install-extension" data-href="<?php echo $extension['install']; ?>"  data-toggle="tooltip" data-original-title="Install"><span class="fa fa-magic"></span></a>
		            <?php } ?>

		            <?php if($extension['updatable'] && $extension['installed']){ ?>
		        	<a class="btn btn-success btn-sm show-loading update-extension" data-href="<?php echo $extension['update']; ?>"  data-toggle="tooltip" data-original-title="Update"><span class="fa fa-refresh"></span></a>
		        	<?php } ?>

		            <?php if($extension['installed']){ ?>
						<a class="btn btn-danger btn-sm show-loading delete-extension" data-href="<?php echo $extension['uninstall']; ?>"  data-toggle="tooltip" data-original-title="Delete"><span class="fa fa-trash-o"></span></a>	
		            <?php } ?>
	                
		        	
		        	<!-- 
		        	<?php if($extension['downloadable'] ){ ?>
		        	<a class="btn btn-default btn-sm download-extension" data-href="<?php echo $extension['download']; ?>"  data-toggle="tooltip" data-original-title="Download"><span class="fa fa-download"></span></a>
			        <?php } ?>
			        -->
					<?php if($extension['purchasable']){ ?>
			        <div class="purchase-extension">
						<div class="form-inline">
							<?php if(!empty($extension['price'])){ ?>
				           
				                <select class="form-control input-sm">
									<?php foreach($extension['prices'] as $price){ ?>
									<option value="<?php echo $price['extension_recurring_price_id']; ?>"><?php echo $price['recurring_price_format']; ?> / <?php echo $price['recurring_duration']; ?> days</option>
									<?php } ?>
								</select>
				     
				            <?php } ?>
					    	<a class="btn btn-primary btn-sm pull-right" data-extension-id="<?php echo $extension['extension_id'];?>"><span class="fa fa-shopping-cart"></span> Buy</a>
				        </div>
					</div>
					<?php } ?>

					<?php if($extension['suspendable'] && !$extension['installed']){ ?>
	                <a class="btn btn-danger btn-sm show-loading suspend-extension" data-href="<?php echo $extension['suspend']; ?>" data-toggle="tooltip" data-original-title="Suspend"><span class="fa fa-ban"></span></a>
		        	<?php } ?>
				<!-- 
		        	<?php if($extension['submittable']){ ?>
	                <a class="btn btn-warning btn-sm  show-loading submit-extension" data-href="<?php echo $extension['submit']; ?>" data-toggle="tooltip" data-original-title="Submit"><span class="fa fa-cloud-upload"></span></a>
		        	<?php } ?> -->	
		        	<?php if($extension['commercial'] && !$extension['purchasable'] && !$extension['installable']){ ?>
					<a class="btn btn-danger btn-sm">Pay invoice</span>
		        	<?php } ?>
		        </div>


		    </div>
	    </div>
	    <div class="ibox-footer clearfix hide">
	    	<div class="rating">
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    		<span class="fa fa-star"></span>
	    	</div>
	    	<div class="reviews"><span class="fa fa-comments"></span> 342</div>
	    	<div class="downloads"><span class="fa fa-cloud-download"></span> 34</div>
	    </div>
	</div>
</div>