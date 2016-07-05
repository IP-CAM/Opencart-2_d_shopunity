<?php
/*
 *	location: admin/view
 */
?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="form-inline pull-right">
				<?php if($stores){ ?>
				<select class="form-control" onChange="location='<?php echo $module_link; ?>&store_id='+$(this).val()">
					<?php foreach($stores as $store){ ?>
					<?php if($store['store_id'] == $store_id){ ?>
					<option value="<?php echo $store['store_id']; ?>" selected="selected" ><?php echo $store['name']; ?></option>
					<?php }else{ ?>
					<option value="<?php echo $store['store_id']; ?>" ><?php echo $store['name']; ?></option>
					<?php } ?>
					<?php } ?>
				</select>
				<?php } ?>
				<button id="save_and_stay" data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" class="btn btn-success"><i class="fa fa-save"></i></button>
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?> <?php echo $version; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if (!empty($error['warning'])) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['warning']; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (!empty($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<a href="<?php echo $logout; ?>" class="btn btn-default pull-right"><?php echo $button_logout; ?></a>
				<ul  class="nav nav-pills">
					<li ><a href="<?php echo $href_extension; ?>" >
						<span class="fa fa-puzzle-piece"></span> 
						<?php echo $tab_extension; ?>
					</a></li>
					<li><a href="<?php echo $href_market; ?>" >
						<span class="fa fa-flask"></span> 
						<?php echo $tab_market; ?>
					</a></li>
					<li class="active"><a href="<?php echo $href_account; ?>" >
						<span class="fa fa-user"></span> 
						<?php echo $tab_account; ?>
					</a></li>
					<li><a href="<?php echo $href_backup; ?>" >
						<span class="fa fa-undo"></span> 
						<?php echo $tab_backup; ?>
					</a></li>
				</ul>

			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<?php echo $profile; ?>
					</div>
					<div class="col-md-9">
						<div class="ibox">
							<div class="ibox-title">
								<ul class="nav nav-tabs">
									<li><a href="<?php echo $href_account; ?>" >
										<span class="fa fa-user"></span> 
										<?php echo $tab_order; ?>
									</a></li>
									<li><a href="<?php echo $href_invoice; ?>" >
										<span class="fa fa-user"></span> 
										<?php echo $tab_invoice; ?>
									</a></li>
									<li class="active"><a href="<?php echo $href_transaction; ?>" >
										<span class="fa fa-user"></span> 
										<?php echo $tab_transaction; ?>
									</a></li>
								</ul>
								<h2>Transactions</h2>
								<p>These are the purchases for the current shop. You may have more purchases for other shops. To view them, visit your account.</p>
							</div>
							<div class="ibox-content">
								 <?php if($transactions){?>
								 <table class="table">
								 	<thead>
								 		<tr>
								 			<th>Order ID</th>
											<th>Description</th>
											<th>Amount</th>
								 		</tr>

								 	</thead>
								 	<tbody>
									<?php foreach($transactions as $transaction){ ?>
										<tr>
											<td>
												<?php echo $transaction['date_added'] ?>
											</td>
											<td>
												<div class="h4 name">#<?php echo $transaction['user_transaction_id'] ?> <?php echo $transaction['description'] ?></div>
											</td>
											<td>
												<?php echo $transaction['amount_format'] ?>
											</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<<!-- pre>
					<?php print_r($transactions); ?>
				</pre> -->	
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>