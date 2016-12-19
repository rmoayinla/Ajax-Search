//javascript //
(function($) {
	$( document ).ready( function() {

		$( "#searchform" ).on( "submit", function( event ){
			event.preventDefault();
			var lightbox, formValues, resultDiv, searchKeyword, _this;
			
			formValues = new FormData(document.getElementById("searchform") );
			formValues.append( "action", "ajax_search" );

			if ( $( ".mini-popup" ).length < 1 ){
				$( "body" ).append( "<div class='lightbox'><div class='mini-popup'> </div></div>" );
			}

			searchKeyword = $( "#search" ).val();
			_this = $(this);

			lightbox = $(".mini-popup");
			lightbox.html("<span class='popup-icon'><i class='fa fa-spin fa-2x fa-refresh'></i></span> Loading search results for <strong> "+ searchKeyword + "</strong>");
			resultDiv = $( "#content" );
			

			$.ajax({
				url: AjaxSearch.ajax_url,
				data: formValues,
				processData: false, 
				contentType: false, 
				type: "POST",
				success: function( data ){
					//alert( "Success" );
					resultDiv.html(data);
					$(".page-header .search-keyword").text(searchKeyword);
				},
				error: function ( xhr, status, message){
					alert( status + ": "+message );
				}, 
				beforeSend: function( xhr ){
					resultDiv.addClass("fade-to");
					lightbox.addClass("slide-in-top").parent().removeClass("hidden");
				}, 
				complete: function (  xhr ){
					resultDiv.removeClass("fade-to");
					lightbox.removeClass("slide-in-top").parent().addClass("hidden");
				}

			}); //.ajax


		} );//input search//

		$( document ).on( "click", ".close-lightbox", function(event){
			$(this).parent().addClass("hidden");
		} );


	}); //document.ready //

})( jQuery ); 