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
		<?php if (!empty($error)) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
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
					<li><a href="<?php echo $href_extension; ?>" >
						<span class="fa fa-puzzle-piece"></span> 
						<?php echo $tab_extension; ?>
					</a></li>
					<li><a href="<?php echo $href_market; ?>" >
						<span class="fa fa-flask"></span> 
						<?php echo $tab_market; ?>
					</a></li>
					<li class="active"><a href="<?php echo $href_account; ?>" >
						<span class="fa fa-cog"></span> 
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
						<?php include(DIR_APPLICATION.'view/template/d_shopunity/extension_thumb.tpl'); ?>

						<?php echo $developer; ?>
					</div>
					<div class="col-md-9">
						<div class="ibox">

							<div class="ibox-title">
								<h2><strong>#<?php echo $order['order_id']; ?></strong>: <?php echo $order['name']; ?></h2>
							</div>
							<div class="ibox-content">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab_history" data-toggle="tab">
										<span class="fa fa-user"></span> 
										<?php echo $tab_history; ?>
									</a></li>
									<li><a href="#tab_invoice" data-toggle="tab">
										<span class="fa fa-user"></span> 
										<?php echo $tab_invoice; ?>
									</a></li>
								</ul>
								<div class="tab-content">
            						<div class="tab-pane active" id="tab_history">
            							<h2>Order History</h2>
										<p>These are the purchases for the current shop. You may have more purchases for other shops. To view them, visit your account.</p>
										<?php if($order['order_history']) {?>
										<table class="table">
										 	<thead>
										 		<tr>
										 			<th>ID</th>
										 			<th>date_added</th>
													<th>description</th>
													<th>order_status_id</th>
										 		</tr>

										 	</thead>
										 	<tbody>
											<?php foreach($order['order_history'] as $order_history){ ?>
												<tr>
													<td>
														<?php echo $order_history['order_history_id'] ?>
													</td>
													<td>
														<?php echo $order_history['date_added'] ?>
													</td>
													<td>
														<?php echo $order_history['description'] ?>
													</td>
													<td>
														<?php echo $order_history['order_status_id'] ?>
													</td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
										<?php } ?>
            						</div>
            						<div class="tab-pane" id="tab_invoice">
            							<h2>Order Invoices</h2>
										<p>These are the purchases for the current shop. You may have more purchases for other shops. To view them, visit your account.</p>
										<?php if($order['order_invoices']) {?>
										<table class="table">
										 	<thead>
										 		<tr>
										 			<th>ID</th>
													<th>name</th>
													<th>date_start</th>
													<th>date_finish</th>
													<th>invoice_status_id</th>
													<th>price (total)</th>
										 		</tr>

										 	</thead>
										 	<tbody>
											<?php foreach($order['order_invoices'] as $invoice){ ?>
												<?php $invoice_order = array(); ?>
												<?php foreach($invoice['invoice_orders'] as $invoice_order) {?>
													<?php if($invoice_order['order_id'] == $order['order_id']) {?>
														<?php break; ?>
													<?php } ?>
												<?php } ?>
												<tr>
													<td>
														<?php echo $invoice['invoice_id'] ?>
													</td>
													<td>
														<?php echo $invoice['name'] ?>
													</td>
													<td>
														<?php echo $invoice_order['date_start'] ?>
													</td>
													<td>
														<?php echo $invoice_order['date_finish'] ?>
													</td>
													<td>
														<?php echo $invoice['invoice_status_id'] ?>
													</td>
													<td>
														<?php echo $invoice_order['recurring_price']; ?>
														(<?php echo $invoice['total_format']; ?>)
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
						<!-- <pre>
						<?php print_r($order); ?>
						</pre> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
		d_shopunity.init({ 
			'purchase_url': '<?php echo $purchase_url; ?>'
		});
</script>
<?php echo $footer; ?>