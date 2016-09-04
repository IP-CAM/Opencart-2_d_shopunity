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
					
						<input type="text" class="form-control fuzzy-search" placeholder="Search for...">
					
				</p>
				
			</div>
		</div>

		<div  id="list_search_1" class="ibox">
			<div class="ibox-title">
				<h4>Purchased modules.</h4>
				<p>These modules have been purchased. You can use them only for this webshop.</p>
			</div>
			<div class="ibox-content">
			<?php if($store_extensions){ ?>
				<ul class="list list-unstyled">
					<?php foreach($store_extensions as $extension) { ?>
						<li>
						<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb_row.tpl'); ?>
						</li>
					<?php } ?>
				</ul>
			<?php }else{ ?>
				<div class="alert alert-warning">You haven't purchased any modules yet</div>
			<?php } ?>
			</div>
		</div>
			
		<div  id="list_search_2" class="ibox"> 
			<div class="ibox-title">
				<h4>Expired or Free licenses.</h4>
			<p>These modules do not have a license, or their lisence has been expired. </p>
			</div>
			<div class="ibox-content">
			<?php if($local_extensions){ ?>
				<ul class="list list-unstyled">
					<?php foreach($local_extensions as $extension) { ?>
						<li>
						<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb_row.tpl'); ?>
						</li>
					<?php } ?>
				</ul>
			<?php }else{ ?>
				<div class="alert alert-warning">There are no expired or free modules installed</div>
			<?php } ?>
			</div>
		</div>
	
		<div  id="list_search_3" class="ibox">
			<div class="ibox-title">
				<h4>Unknown Modules.</h4>
				<p>These modules are not regestered with the shopunity network.</p>
			</div>
			<div class="ibox-content">
			<?php if($unregestered_extensions){ ?>
				<ul class="list list-unstyled">
					<?php foreach($unregestered_extensions as $extension) { ?>
					<li>
						<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb_row.tpl'); ?>
					</li>
					<?php } ?>
				</ul>
			<?php }else{ ?>
				<div class="alert alert-warning">You don't have any unregestered modules installed</div>
			<?php } ?>
			</div>
	 <!-- <pre>

			
		<?php print_r($store_extensions );?>
	</pre>  
	<pre>
		<?php print_r($local_extensions );?>
	</pre>    -->
		</div>
	</div>
</div>
<script>
	
	var options = {
	  valueNames: [ 'name' ], 
	  plugins: [ ListFuzzySearch() ]
	};

	var userList1 = new List('list_search_1', options);
	var userList2 = new List('list_search_2', options);
	var userList3 = new List('list_search_3', options);

	$('.fuzzy-search').on("keyup",function(){
        userList1.search($(this).val());
        userList2.search($(this).val());
        userList3.search($(this).val());
    }); 

</script>
<?php echo $content_bottom; ?>
