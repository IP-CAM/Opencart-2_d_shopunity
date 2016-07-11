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
			<div class="ibox-content">
		<?php if($extension['store_extension'] || empty($extension['prices'])){ ?>
			<?php if(!$extension['installed']){?>
	                <a class="btn btn-primary btn-block" href="<?php echo $extension['install']; ?>"><span class="fa fa-cloud-download"></span> Install</a>
	           		<a class="btn btn-danger btn-block" href="<?php echo $extension['suspend']; ?>"><span class="fa fa-ban"></span> Suspend</a>
	    	<?php }else{ ?>
	                <a class="btn btn-success btn-block" href="<?php echo $extension['update']; ?>"><span class="fa fa-refresh"></span> Update</a>
	                <a class="btn btn-info btn-block" href="<?php echo $extension['download']; ?>" ><span class="fa fa-download"></span> Download</a>
	                <a class="btn btn-danger btn-block" href="<?php echo $extension['uninstall']; ?>"><span class="fa fa-trash-o"></span> Delete</a>
	            
	    	<?php } ?>
        <?php }else{ ?>
        	<?php if(!empty($extension['price'])){ ?>
			<div class="purchase">
				<h4>Select payment plan</h4>
				<p>By purchasing a payment plan for an extension, you will be granted 1 license per domain for the period provided. An order will be created with recurring payment until canceled.</p>
				<select class="form-control">
					<?php foreach($extension['prices'] as $price){ ?>
					<option value="<?php echo $price['extension_recurring_price_id']; ?>"><?php echo $price['recurring_price']; ?></option>
					<?php } ?>
				</select>
				<br/>
				<a class="btn btn-primary btn-block btn-lg" href="<?php echo $purchase; ?>"><span class="fa fa-cloud-download"></span> Get it!</a>
				
			</div>
	        <?php } ?>
        <?php } ?>
			</div>
    	</div>
        <?php echo $developer; ?>
	</div>
	<div class="col-md-9">
		<div class="image">
			<img src="<?php echo $extension['processed_images'][2]['url']; ?>" class="img-responsive"/>
		</div>
		<div class="description"><?php echo html_entity_decode($extension['description']); ?></div>
		<pre>	
			<?php print_r($extension );?>
		</pre> 
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