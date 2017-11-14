/**
* General & misc  JS script snippets
*
*/
( function( $ ) {
	var  viewportWidth, isFocused;

/**
* Handle seeting / removing classes and display properties
* for user/group object nav & main site navigation
*
*/
	function browserSize() {

		// Set a class of mobile-menu on browser resize & load if browser
		// width less than 760px.
		// Remove classes 'mobile-menu' & 'toggled' if greater than 750px.
		// Hide & show user/group nav in main nav header or in item-body.
		viewportWidth = ( window.innerWidth || document.documentElement.clientWidth);

		if(viewportWidth <= '750') {
			$('#site-navigation').addClass('mobile-menu');
			$('.site-header .bp-object-nav').addClass('hidden').hide();
			$('.manage-object-nav-visibility').show();
		}

		if(viewportWidth >= '750' && $('#site-navigation').hasClass('mobile-menu') ) {
			$('#site-navigation').removeClass('mobile-menu');
		}

		if(viewportWidth >= '750' && $('#site-navigation').hasClass('toggled') ) {
			$('#site-navigation').removeClass('toggled');
		}

		if(viewportWidth >= '750' && $('.site-header .bp-object-nav').hasClass('hidden') ) {
			$('.site-header .bp-object-nav').show();
			$('.manage-object-nav-visibility').hide();
			$('.site-header .bp-object-nav').removeClass('hidden');
		}
	}
	window.addEventListener('resize', browserSize);
	window.addEventListener('load', browserSize);


	var field1 = document.getElementById( 'pass1' );
	var field2 = document.getElementById( 'pass2' );

	if (field1 || field2 ) {

		$( field1 ).on( 'focus', function() {
			field1.type = 'text';
			field2.type = 'text';
		});

		$( field2 ).on( 'focus', function() {
			field1.type = 'text';
			field2.type = 'text';
		});

		(field1 && field2).addEventListener( 'blur', function() {
			field1.type = 'password';
			field2.type = 'password';
		});
	}

 var selectItem = $(".mepr-select-field option:first");
	var selectOptionVal = $(".mepr-select-field option:first").val();
	if ( !selectOptionVal ) {
		$( selectItem ).text('Select One').attr('disabled', true);
	}

	} )( jQuery );
