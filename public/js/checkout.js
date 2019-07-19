jQuery(document).ready(function($){
    $(".first").each(function(){
	$(this).on("click", function(){
	    var image = $(this).data('image');
	    $("#im_a").attr('href', image);
	    $("#im").attr('src', image).attr('data-echo', image);
	    $(this).addClass("active_a").removeClass("no_active");
	    
	    $(".first").each(function(){
		if ($(this).data('image') != image) {
		    $(this).addClass("no_active");
		}
	    });
	});
    });
});
