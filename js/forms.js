$(document).ready(function() {
	// Close all the member info fieldsets.
	$(".member_info").addClass('closed');
	$("div", ".member_info").hide();
	// Get the number of participants.
	var participants = $('select', '.participants');
	var i = parseInt(participants.val());
	// Display the appropriate field: Class or major
	if ( i > 1 ) {
		$(".class").show();
	} else {
		$(".class").hide();
	}
	// Display appropriate amount of member info fieldsets.
	for ( ; i < 10; i++ ) {
		$("#member_info_"+i).hide();
	}
	// Show or hide appropriate fieldsets based on account_type choice.
	$('select', $('.account_type')).on('change', function() {
		var val = $(this).val();
		switch( val ) {
			case 'pa':
				$('.database_fieldset').hide();
				$('.project_fieldset').show();
				break;
			case 'db':
				$('.database_fieldset').show();
				$('.project_fieldset').hide();
				break;
			default:
				$('.database_fieldset').show();
				$('.project_fieldset').show();
		}
	});
	// Display appropriate amount of member info fieldsets.
	participants.on('change', function() {
		var i = parseInt($(this).val());
		if ( i > 1 ) {
			$(".class").show();
		} else {
			$(".class").hide();
		}
		for ( var j = i; j < 10; j++ ) {
			$("#member_info_"+j).hide();
		}
		for ( var k = i - 1; k >= 0; k-- ) {
			$("#member_info_"+k).show();
		}
	});
	// If the mysql permissions radio changes, change the checkboxes.
	$('.permissions input:radio').on('change', function() {
		var val = $(this).val();
		switch ( val ) {
			case 'all':
				$.each($("input:checkbox", $(".other_permissions")), function() {
					$(this).prop('checked', 'checked');
				});
				break;
			case 'std':
				$.each($("input:checkbox", $(".other_permissions")), function() {
					var pattern = /(select|insert|update|delete)/;
					if ( pattern.test($( this ).val() ) ) {
						$(this).prop('checked', 'checked');
					} else {
						$(this).prop('checked', '');
					}
				});
				break;
		}
	});
	// Add the fieldset click handlers to collapse and expand fieldsets.
	$('.fieldset > h3').on('click', function() {
		var fieldset = $(this).parent('div');
		if ( fieldset.hasClass( "closed" ) ) {
			$("*", fieldset).show();
			fieldset.removeClass("closed");
		} else {
			$("*:not(h3)", fieldset).hide();
			$(fieldset).addClass("closed");
		}
	});
	
});