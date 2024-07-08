$(document).ready(function(){

	// Login / Signup Form

	$("a#login").click(function(){
    	$("input#login").prop("checked",true);
       	$("#login-form").show('fast');
    });

    $("a#register").click(function(){
    	$("input#signup").prop("checked",true);
    	$("#login-form").show('fast');
    });

    $(".close, .content, footer").click(function(){
        $("#login-form").hide('fast');
        $('.content, footer,header').css("pointer-events","auto");
    });
    
	// scroll to any position

	$("a[href^='#']").on("click", function( e ) {
    	e.preventDefault();
    	var target = $(this).attr('href');
    	$("body, html").animate({ 
      		scrollTop: $(target).offset().top 
      	}, 600);

  	});

	// Loading box

	var timer = setInterval(loading,0);
	var x =1;
	function loading(){
		x++; 
		$(".content, footer,header, .clr-switch").hide();
		if (x>50){
			clearInterval(timer);
			$(".cssload-loader").hide();
			$(".content, footer,header, .clr-switch").show();
		}
	}

	// Color Switcher 

	$(".clr-switch").click(function(){
		$(".bubble-1, .bubble-2, .bubble-3").toggle();
	});

	$(".bubble-1").click(function(){
		document.body.style.setProperty('--unique-color',"#0ebc7f");
		$(".bubble-1, .bubble-2, .bubble-3").hide();
	});

	$(".bubble-2").click(function(){
		document.body.style.setProperty('--unique-color',"#22aaf9");
		$(".bubble-1, .bubble-2, .bubble-3").hide();
	});

	$(".bubble-3").click(function(){
		document.body.style.setProperty('--unique-color',"#d84e49");
		$(".bubble-1, .bubble-2, .bubble-3").hide();
	});

	// Carousel 

	var slide = 1;
	$("i.arrow-back").mouseover(function(){
		$(".text-back").show();
		$(".text-back").css("animation","fadeInLeft 0.7s ease-out");
	});
	$("i.arrow-back").mouseleave(function(){
		$(".text-back").hide();
		
	});

	$("i.arrow-forward").mouseover(function(){
		$(".text-forward").show();
		$(".text-forward").css("animation","fadeInRight 0.7s ease-out");
	});
	$("i.arrow-forward").mouseleave(function(){
		$(".text-forward").hide();
	});

	$("i.arrow-back").click(function(){
		slide -=1;
		if (slide < 1){
			slide=3;
		}

		$(".col_one_third").css("animation","fadeInLeft 0.6s ease-out");
		$("#about-img").css("animation","fadeInRight 0.4s ease-out");
		
	});
	
	$("i.arrow-forward").click(function(){
		slide +=1;
		if(slide>3){
			slide = 1;
		}
		$(".col_one_third").css("animation","fadeInRight 0.6s ease-out");
		$("#about-img").css("animation","fadeInLeft 0.4s ease-out");
		
	});

	$(".arrow").click(function(){
		if (slide ==1){
		$(".radio-1").html("radio_button_checked");
		$(".radio-2").html("radio_button_unchecked");
		$(".radio-3").html("radio_button_unchecked");
		$(".col_1st").show();
		$(".col_2nd").hide();
		$(".col_3rd").hide();
		$("#about-img").prop("src","images/we.png");
		$(".text-back").text("What we provide");
		$(".text-forward").text("Our mission");
	}
	else if (slide==2){
		$(".radio-1").html("radio_button_unchecked");
		$(".radio-2").html("radio_button_checked");
		$(".radio-3").html("radio_button_unchecked");
		$(".col_1st").hide();
		$(".col_2nd").show();
		$(".col_3rd").hide();
		$("#about-img").prop("src","images/mission.png");
		$(".text-back").text("Who are we");
		$(".text-forward").text("What we provide");
	}
	else if (slide==3){
		$(".radio-1").html("radio_button_unchecked");
		$(".radio-2").html("radio_button_unchecked");
		$(".radio-3").html("radio_button_checked");
		$(".col_1st").hide();
		$(".col_2nd").hide();
		$(".col_3rd").show();
		$("#about-img").prop("src","images/provide.png");
		$(".text-back").text("Our mission");
		$(".text-forward").text("Who are we");
	}
	});

	$(".text-back").text("What we provide");
	$(".text-forward").text("Our mission");
	$(".col_2nd").hide();
	$(".col_3rd").hide();

    // Google Maps API

    $("#map").hide();
    $("#location").mouseover(function(){
    	$(".contact-detail").hide();

    	$("#map").show().css("animation","zoomIn 0.5s ease-in");
		var istudent = {lat: 33.6422734, lng: 72.9904328};
        var map = new google.maps.Map(document.getElementById('map'), {
        	zoom: 15,
        	center: istudent
        });
        var marker = new google.maps.Marker({
        	position: istudent,
        	map: map,
        	animation: google.maps.Animation.BOUNCE
        });

        var infowindow = new google.maps.InfoWindow({
    	content: "iStudent Center"
  		});

  		google.maps.event.addListener(marker, 'click', function() {
  			infowindow.open(map,marker);
  		});
  	});

    $("#map").mouseleave(function(){
    	$(".contact-detail").show();
    	$("#map").hide();
    });
    
});