<div class="extension-thumb col-md-4 col-sm-6">
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
		        </p>
		        
		        <?php if($extension['store_extension'] || empty($extension['prices'])){ ?>
					<?php if(!$extension['installed']){?>
			        <div class="pull-right">
		                <a class="btn btn-info show-loading install-extension" data-href="<?php echo $extension['install']; ?>"  data-toggle="tooltip" data-original-title="Install"><span class="fa fa-plus"></span></a>
		                <a class="btn btn-danger show-loading suspend-extension" data-href="<?php echo $extension['suspend']; ?>" data-toggle="tooltip" data-original-title="Suspend"><span class="fa fa-ban"></span></a>
			        </div>
			    	<?php }else{ ?>
			        <div class="pull-right">
			            	<?php if($extension['registered']){ ?>
			                <a class="btn btn-success show-loading update-extension" data-href="<?php echo $extension['update']; ?>"  data-toggle="tooltip" data-original-title="Update"><span class="fa fa-refresh"></span></a>
			            	<?php } ?>
			            	<a class="btn btn-default download-extension" data-href="<?php echo $extension['download']; ?>"  data-toggle="tooltip" data-original-title="Download"><span class="fa fa-download"></span></a>
			            	<a class="btn btn-danger show-loading delete-extension" data-href="<?php echo $extension['uninstall']; ?>"  data-toggle="tooltip" data-original-title="Delete"><span class="fa fa-trash-o"></span></a>
			        </div>
			    	<?php } ?>
		        <?php }else{ ?>
		        	<?php if(!empty($extension['price'])){ ?>
					<div class="purchase-extension">
						<div class="row ">
				            <div class="col-xs-12 col-md-6">
				                <select class="form-control">
									<?php foreach($extension['prices'] as $price){ ?>
									<option value="<?php echo $price['extension_recurring_price_id']; ?>"><?php echo $price['recurring_price']; ?></option>
									<?php } ?>
								</select>
				            </div>
				            <div class="col-xs-12 col-md-6">
				                <a class="btn btn-primary pull-right" data-extension-id="<?php echo $extension['extension_id'];?>">Buy</a>
				            </div>
				        </div>
					</div>
			        <?php } ?>
		        <?php } ?>
		    </div>
	    </div>
	    <div class="ibox-footer clearfix">
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