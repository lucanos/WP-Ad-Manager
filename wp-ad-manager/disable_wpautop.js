jQuery(document).ready(function(){
	jQuery('body').bind( 'beforePreWpautop', function(event, data){editor_data=data.data} );
	jQuery('body').bind( 'afterPreWpautop', function(event, data){data.data = editor_data;} );
	jQuery('body').bind( 'beforeWpautop', function(event, data){editor_data=data.data} );
	jQuery('body').bind( 'afterWpautop', function(event, data){data.data = editor_data;} );
	});