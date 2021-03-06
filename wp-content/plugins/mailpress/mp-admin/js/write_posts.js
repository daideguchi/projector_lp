// write_posts

jQuery(document).ready( function() {
	jQuery('#mppostchecklist').sortable({ 
		axis: 'y', 
		containment: '#mppostchecklist', 
		cursor: 'move', 
		handle: '.mppost-handle',
		opacity: 0.8,
		update: function(event, ui) {
			var data = { 
				action : 'mp_ajax',
				mp_action : 'order_mppost',
				id: jQuery("#mail_id").val(),
				posts : jQuery(this).sortable('toArray').toString(),
				_ajax_nonce : mp_postsL10n.order_mppost
			};

			jQuery.ajax({
				data: data,
				type: "POST",
				url: autosaveL10n.requestFile
			});
		}
	});
	jQuery('#mppostchecklist').wpList({
		response: 'mppost-ajax-response',
		addBefore: function( s ) {
			s.data += '&mail_id=' + jQuery('#mail_id').val(); 
			return s;
		},
		addAfter: function( xml, s ) {
			jQuery('table#mppostchecklist').show();
		}, 
		delBefore: function( s ) {
			s.data.mail_id = jQuery('#mail_id').val();
			return s;
		},
		delAfter: function( r, settings ) {
			jQuery('#mppost-' + r).remove();
		}
	});
});
