<?php
/*
 *	location: admin/view
 */
?>
<?php echo $content_top; ?>
<div class="row">
	
	<div class="col-md-12">
		<div class="ibox">
			<div class="ibox-title">
				<h4>Search for extensions</h4>
				<p>
					<div class="input-group input-group-lg">
						<input type="text" class="form-control" placeholder="Search for...">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Go!</button>
						</span>
					</div><!-- /input-group -->
				</p>
				
			</div>
		</div>

		<div class="ibox">
			<div class="ibox-title">
				<h4>Purchased modules.</h4>
				<p>These modules have been purchased. You can use them only for this webshop.</p>
			</div>
		
			<?php if($store_extensions){ ?>
			<div class="ibox-content">
				<?php foreach($store_extensions as $extension) { ?>

					<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb_row.tpl'); ?>

				<?php } ?>
			</div>
			<?php }else{ ?>
				<div class="bs-callout bs-callout-info">No store modules to display</div>
			<?php } ?>
		</div>
			
		<div class="ibox"> 
			<div class="ibox-title">
				<h4>Expired licenses.</h4>
			<p>These modules do not have a license, or their lisence has been expired. </p>
			</div>
			<?php if($local_extensions){ ?>
			<div class="ibox-content">
				<?php foreach($local_extensions as $extension) { ?>
					
					<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb_row.tpl'); ?>
					
				<?php } ?>
			</div>
			<?php }else{ ?>
				<div class="bs-callout bs-callout-info">No local modules to display</div>
			<?php } ?>
		</div>
	
		<div class="ibox">
			<div class="ibox-title">
				<h4>Unknown Modules.</h4>
				<p>These modules are not regestered with the shopunity network.</p>
			</div>
			<?php if($unregestered_extensions){ ?>
			<div class="ibox-content">
				<?php foreach($unregestered_extensions as $extension) { ?>

					<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb_row.tpl'); ?>

				<?php } ?>
			</div>
			<?php }else{ ?>
				<div class="bs-callout bs-callout-info">No local modules to display</div>
			<?php } ?>
	 <!-- <pre>

			
		<?php print_r($store_extensions );?>
	</pre>  
	<pre>
		<?php print_r($local_extensions );?>
	</pre>    -->
		</div>
	</div>
</div>
<?php echo $content_bottom; ?>
