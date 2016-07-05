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
									<li  class="active"><a href="<?php echo $href_invoice; ?>" >
										<span class="fa fa-user"></span> 
										<?php echo $tab_invoice; ?>
									</a></li>
									<li><a href="<?php echo $href_transaction; ?>" >
										<span class="fa fa-user"></span> 
										<?php echo $tab_transaction; ?>
									</a></li>
								</ul>
								<h2>Invoices</h2>
								<p>These are the purchases for the current shop. You may have more purchases for other shops. To view them, visit your account.</p>
							</div>
							<div class="ibox-content">
								 <?php if($invoices){?>
								 <table class="table">
								 	<thead>
								 		<tr>
								 			<th>Order ID</th>
											<th>Description</th>
											<th>Status</th>
											<th>Total</th>
											<th>Action</th>
								 		</tr>

								 	</thead>
								 	<tbody>
									<?php foreach($invoices as $invoice){ ?>
										<tr>
											<td>
												<?php echo $invoice['invoice_id'] ?>
											</td>
											
											<td>
												<a href="<?php echo $invoice['url']; ?>">
													<div class="h4 name"><?php echo $invoice['name'] ?></div>
												</a>
												<p class="description"><?php echo $invoice['date_added'] ?> </p>
												<?php if(!$invoice['invoice_status_id']){ ?>
													<div class="alert alert-danger">
														This invoice is overdue - please pay it as soon as possible.
													</div>
	
												<?php } ?>
											</td>
											<td>
												<?php echo $invoice['invoice_status_id'] ?>
											</td>
											<td>
												<?php echo $invoice['total_format'] ?>
											</td>
											<td>
												
										        <div class="pull-right">
									                <?php if(!$invoice['invoice_status_id']){ ?>
									                <a class="btn btn-success" href="<?php echo $invoice['pay']; ?>" data-toggle="tooltip" data-original-title="Pay"><span class="fa fa-money"></span></a>
										       		<?php } ?>
									                <a class="btn btn-info	" href="<?php echo $invoice['url']; ?>" data-toggle="tooltip" data-original-title="View"><span class="fa fa-eye"></span></a>

										        </div>
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
					<?php print_r($invoices); ?>
				</pre>	 -->
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>