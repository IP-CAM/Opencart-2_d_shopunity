<div class="ibox account-profile">
	<div class="ibox-content">
		<div class="image">
			<img src="<?php echo $account['image']; ?>" class="img-responsive"/>
		</div>
		<div class="caption">
			
			<div class="h1 username"><?php echo $account['username']; ?></div>
			<div class="h2 balance"><?php echo $account['balance_format']; ?>
				<!-- <span class="btn btn-success btn-xs add-to-balance"><span class="fa fa-plus"></span></span> -->
			</div>
			<div class="h4 name"><?php echo $account['firstname']; ?> <?php echo $account['lastname']; ?></div>
		</div>
		<!-- <div class="view-profile">
			<a href="<?php echo $account['profile_url']; ?>" target="_blank" class="btn btn-info ">View profile</a>
		</div> -->
	</div>
</div>

<!-- 	<pre>
		<?php print_r($account); ?>

	</pre> -->