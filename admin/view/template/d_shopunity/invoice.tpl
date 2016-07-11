<?php
/*
 *	location: admin/view
 */
?>
<?php echo $content_top; ?>
<div class="row">
	<div class="col-md-3">
		<?php echo $profile; ?>
	</div>
	<div class="col-md-9">
		<div class="ibox">
			<div class="ibox-title">
				<ul class="nav nav-tabs">
					<li><a href="<?php echo $href_order; ?>" >
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
<?php echo $content_bottom; ?>