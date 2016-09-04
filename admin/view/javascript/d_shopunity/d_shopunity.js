d_shopunity = {

	setting: {
		'purchase_url' : '', //set admin url with token
	},

	init: function(setting){
		this.setting = $.extend({}, this.setting, setting);
		this.render();
	},

	purchaseExtension: function(extension_id, extension_recurring_price_id){
		var href = this.setting.purchase_url;
			that = this;
		swal({	
			title: "Pusrchase this Extension",	
			text: "You are about to purchase this extension!",	
			type: "info",	
			showCancelButton: true, 
			confirmButtonColor: "#5bc0de",	
			confirmButtonText: "Yes, Purchase it!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(isConfirm){  
			if (isConfirm) {    
				href += '&extension_id='+extension_id+'&extension_recurring_price_id='+extension_recurring_price_id;
				location.href = href;
			} else {     
				that.hideLoading($('.loading'));
		 	}	
		});

		return false;

		
	},

	installExtension: function($node){
		var that = this;
		$.ajax({
			url: $node.data('href'),
			dataType: 'json',
			method: 'get',
			success: function(json) {
				if(json['installed']){
					swal({	
						title: "Installed",	
						text: json['text'],	
						type: "success",	
						showCancelButton: true, 
						confirmButtonColor: "#AEDEF4",	
						confirmButtonText: "View",	
						closeOnConfirm: false,
						closeOnCancel: true
					},
					function(isConfirm){  
						if (isConfirm) {
							location.href = json['view'];
						} else {     
							that.hideLoading($('.loading'));
					 	}	
					});
				}

				if(json['extension']){
					$('#extension_'+json['codename']).replaceWith(json['extension']);
				}
			}
		}); 
		return false;
		
	},

	updateExtension: function($node){
		var that = this;
		swal({	
			title: "Update this Extension",	
			text: "You are about to update this extension!",	
			type: "info",	
			showCancelButton: true, 
			confirmButtonColor: "#8fbb6c",	
			confirmButtonText: "Yes, Update it!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(isConfirm){  
			if (isConfirm) {     
				location.href = $node.data('href');  
			} else {     
				that.hideLoading($('.loading'));
		 	}	
		});

		return false;
		
	},

	downloadExtension: function($node){

		location.href = $node.data('href');
	},

	deleteExtension: function($node){
		var that = this;
		swal({	
			title: "Delete this Extension",	
			text: "You are about to delete this extension!",	
			type: "warning",	
			showCancelButton: true, 
			confirmButtonColor: "#f56b6b",	
			confirmButtonText: "Yes, Delete it!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(isConfirm){  
			if (isConfirm) {     
				$.ajax({
					url: $node.data('href'),
					dataType: 'json',
					method: 'get',
					success: function(json) {
						if(json['uninstalled']){
							swal({	
								title: "Uninstalled",	
								text: json['text'],	
								type: "success",	
								showCancelButton: true, 
								confirmButtonColor: "#AEDEF4",	
								confirmButtonText: "View",	
								closeOnConfirm: false,
								closeOnCancel: true
							},
							function(isConfirm){  
								if (isConfirm) {
									location.href = json['view'];
								} else {     
									that.hideLoading($('.loading'));
							 	}	
							});
						}

						if(json['extension']){
							$('#extension_id_'+json['extension_id']).replaceWith(json['extension']);
						}
					}
				});  
			} else {     
				that.hideLoading($('.loading'));
		 	}	
		});

		return false;
		
	},

	suspendExtension: function($node){
		var that = this;
		swal({	
			title: "Cancel purchase of this Extension",	
			text: "You are about to Cancel purchase of this extension!",	
			type: "warning",	
			showCancelButton: true, 
			confirmButtonColor: "#f56b6b",	
			confirmButtonText: "Yes, Cancel this purchase!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(isConfirm){  
			if (isConfirm) {     
				location.href = $node.data('href');  
			} else {     
				that.hideLoading($('.loading'));
		 	}	
		});

		return false;
		
	},

	submitExtension: function($node){
		var that = this;
		swal({	
			title: "Submit extension to shopunity.net",	
			text: "You are about to start a submission process of this extension!",	
			type: "warning",	
			showCancelButton: true, 
			confirmButtonColor: "#f56b6b",	
			confirmButtonText: "Yes, submit this extension!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(isConfirm){  
			if (isConfirm) {     
				location.href = $node.data('href');  
			} else {     
				that.hideLoading($('.loading'));
		 	}	
		});

		return false;
		
	},

	approveExtension: function($node){
		var that = this;
		swal({	
			title: "Approve this extension",	
			text: "You are about to approve this extension!",	
			type: "warning",	
			showCancelButton: true, 
			confirmButtonColor: "#f56b6b",	
			confirmButtonText: "Yes, approve this extension!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(isConfirm){  
			if (isConfirm) {     
				location.href = $node.data('href');  
			} else {     
				that.hideLoading($('.loading'));
		 	}	
		});

		return false;
		
	},

	disapproveExtension: function($node){

		var that = this;
		swal({	
			title: "Disapprove this extension",	
			text: "You are about to disapprove this extension!",	
			type: "input", 	
			inputPlaceholder: "Tester comment",
			showCancelButton: true, 
			confirmButtonColor: "#f56b6b",	
			confirmButtonText: "Yes, disapprove this extension!",	
			closeOnConfirm: false,
			closeOnCancel: true
		}, 
		function(inputValue){   
			if (inputValue === false) return false;      
			if (inputValue === "") {     
				swal.showInputError("You need to write something!");     
				return false 
			}      

			$.ajax({
				url: $node.data('href'),
				dataType: 'html',
				data: 'tester_comment='+inputValue,
				method: 'post',
				success: function(html) {
					console.log(html)
					location.reload();
				}
			});
		});
	

		return false;

	},

	showExtensionJson: function($node){

		$("#myModal").modal('show');
		$.ajax({
			url: $node.data('href'),
			dataType: 'json',
			success: function(json) {
				$("#myModal").find(".modal-body").html(syntaxHighlight(json));
	
			}
		});
		
	},

	showLoading: function($loading){
		$loading.addClass('show');
	},

	hideLoading: function($loading){
		$loading.removeClass('show');
	},

	search: function($node){
		location.href = $node.data('href') + '&search=' + $('input[name=search]').val();
	},

	render: function(){
		var that = this;

		$(document).on('click', '.purchase-extension .btn', function(){
			that.purchaseExtension($(this).data('extension-id'), $(this).parents('.purchase-extension').find('select').val());
		});

		$(document).on('click', '.install-extension', function(){
			that.installExtension($(this));
		});

		$(document).on('click', '.test-extension', function(){
			that.installExtension($(this));
		});

		$(document).on('click', '.download-extension', function(){
			that.downloadExtension($(this));
		});

		$(document).on('click', '.update-extension', function(){
			that.updateExtension($(this));
		});

		$(document).on('click', '.delete-extension', function(){
			that.deleteExtension($(this));
		});

		$(document).on('click', '.suspend-extension', function(){
			that.suspendExtension($(this));
		});

		$(document).on('click', '.submit-extension', function(){
			that.submitExtension($(this));
		});

		$(document).on('click', '.approve-extension', function(){
			that.approveExtension($(this));
		});

		$(document).on('click', '.disapprove-extension', function(){
			that.disapproveExtension($(this));
		});

		$(document).on('click', '.show-loading', function(){
			that.showLoading($(this).parents('.extension-thumb').find('.loading'));
		});

		$(document).on('click', '.show-extension-json', function(){
			that.showExtensionJson($(this));
		});

		$(document).on('click', '.search', function(){
			that.search($(this));
		});
	}

};
