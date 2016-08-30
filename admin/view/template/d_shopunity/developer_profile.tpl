<div class="ibox developer-profile">
	<div class="ibox-content">
		<div class="image">
			<img src="<?php echo $developer['image']; ?>" class="img-responsive"/>
		</div>
		<div class="caption">
			<!-- <div class="rating"><span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span>
				<span class="fa fa-star"></span></div> -->
			<div class="h1 username"><?php echo $developer['name']; ?></div>
			<p class="description"><?php echo $developer['description']; ?></p>
		</div>
		<!-- <div class="extensions">
			<a class="btn btn-default">
				<span class="fa fa-puzzle-piece"></span> 76 extensions
			</a>
		</div>
		<div class="links">
			<a href="mailto:<?php echo $account['email']; ?>" target="_blank" class="btn btn-info ">Support</a>
			<a href="<?php echo $developer['website']; ?>"  target="_blank" class="btn btn-info ">Website</a>
		</div> -->
	</div>
</div>

<!-- 	<pre>
		<?php print_r($developer); ?>

	</pre> -->