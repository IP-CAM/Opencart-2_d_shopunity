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
		location.href = $node.data('href');  
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
				location.href = $node.data('href');  
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



	showLoading: function($loading){
		$loading.addClass('show');
	},

	hideLoading: function($loading){
		$loading.removeClass('show');
	},

	render: function(){
		var that = this;

		$(document).on('click', '.purchase-extension .btn', function(){
			that.purchaseExtension($(this).data('extension-id'), $(this).parents('.purchase-extension').find('select').val());
		});

		$(document).on('click', '.install-extension', function(){
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

		$(document).on('click', '.show-loading', function(){
			that.showLoading($(this).parents('.extension-thumb').find('.loading'));
		});



	}

};
