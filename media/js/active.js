$(document).ready(function() {
	
	$(".enter-to-site").click(function(){
		$(".hidden-login").animate( { top:"50%" }, { duration:500 } );
		$(".hidden-body").show("slow");
	});
	
	$(".login-close").click(function(){
		$(".hidden-login").animate( { top:"-100%" } );
		$(".hidden-body").hide("slow");
	});
	
	$(".eerwer img:first-child").addClass("first-img");
	
	$(".hover-more").hover(
		function() {
			$(this).find(".news-item-more a").animate({			
				marginLeft: "-30px"
			  }, 100 );
			
		}, function() {
			$(this).find(".news-item-more a").animate({			
				marginLeft: "0px"
			  }, 200 );
	});
	
	$(".anons-film").hover(
		function() {
			$(this).find(".anons-film-content-category").animate({			
				bottom: "0px"
			  }, 400 );
			
		}, function() {
			$(this).find(".anons-film-content-category").animate({			
				bottom: "-20px"
			  }, 100 );
	});
	
	$("a, img, div").easyTooltip();
	
	
});
