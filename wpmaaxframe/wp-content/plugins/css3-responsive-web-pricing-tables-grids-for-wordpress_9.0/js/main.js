jQuery(document).ready(function($){
	function css3GridSetWidth(id)
	{
		$("#"+id).css("width", ($("#"+id+" .caption_column").is(":visible") ? $("#"+id+" .caption_column").width() : 0) + $("#"+id+" .caroufredsel_wrapper").width() + "px");
		$("#css3_grid_" + id + "_slider_container, .css3_grid_" + id + "_pagination").css("width", ($("#"+id+" .caption_column").is(":visible") ? $("#"+id+" .caption_column").width() : 0) + $("#"+id+" .caroufredsel_wrapper").width() + (2*$("#css3_grid_" + id + "_slider_container .css3_grid_arrow_area").outerWidth()) + "px");				
		$("#"+id+" .caroufredsel_wrapper").css("height", $("#"+id+" .caption_column").height() + "px");
		$(".p_table_1 .css3_grid_hidden_rows_control_"+id).css("width", ($("#"+id+" .caption_column").is(":visible") ? $("#"+id+" .caption_column").width() : 0) + $("#"+id+" .caroufredsel_wrapper").width()-2 + "px");
		$(".p_table_2 .css3_grid_hidden_rows_control_"+id).css("width", ($("#"+id+" .caption_column").is(":visible") ? $("#"+id+" .caption_column").width() : 0) + $("#"+id+" .caroufredsel_wrapper").width() + "px");
	};
	var items = 1, autoplay = 0, effect = 'scroll', easing = 'swing', duration = 500, id;
	$(".css3_grid_slider").each(function(){
		var self = $(this);
		var elementClasses = $(this).attr('class').split(' ');
		for(var i=0; i<elementClasses.length; i++)
		{
			if(elementClasses[i].indexOf('id-')!=-1)
				id = elementClasses[i].replace('id-', '');
			if(elementClasses[i].indexOf('autoplay-')!=-1)
				autoplay = elementClasses[i].replace('autoplay-', '');
			if(elementClasses[i].indexOf('items-')!=-1)
				items = elementClasses[i].replace('items-', '');
			if(elementClasses[i].indexOf('scroll-')!=-1)
				scroll = elementClasses[i].replace('scroll-', '');
			if(elementClasses[i].indexOf('effect-')!=-1)
				effect = elementClasses[i].replace('effect-', '');
			if(elementClasses[i].indexOf('easing-')!=-1)
				easing = elementClasses[i].replace('easing-', '');
			if(elementClasses[i].indexOf('duration-')!=-1)
				duration = elementClasses[i].replace('duration-', '');
			if(elementClasses[i].indexOf('threshold-')!=-1)
				threshold = elementClasses[i].replace('threshold-', '');
		}
		var carouselOptions = {
			/*circular: false,
			infinite: false,*/
			items: parseInt(items),
			prev: {
				items: parseInt(scroll),
				button: $('#css3_grid_' + id + '_prev'),
				fx: effect,
				easing: easing,
				duration: parseInt(duration)
			},
			next: {
				items: parseInt(scroll),
				button: $('#css3_grid_' + id + '_next'),
				fx: effect,
				easing: easing,
				duration: parseInt(duration)
			},
			auto: {
				items: parseInt(scroll),
				play: (parseInt(autoplay) ? true : false),
				fx: effect,
				easing: easing,
				duration: parseInt(duration)
			}
		};
		if(self.hasClass('ontouch') || self.hasClass('onmouse'))
			carouselOptions.swipe = {
				items: parseInt(scroll),
				onTouch: (self.hasClass('ontouch') ? true : false),
				onMouse: (self.hasClass('onmouse') ? true : false),
				options: {
					allowPageScroll: "none",
					threshold: parseInt(threshold)
				},
				fx: effect,
				easing: easing,
				duration: parseInt(duration)
			};
		if(self.hasClass('pagination'))
			carouselOptions.pagination = {
				items: parseInt(scroll),
				container: $(".css3_grid_" + id + "_pagination"),
				fx: effect,
				easing: easing,
				duration: parseInt(duration)
			};
		$(this).carouFredSel(carouselOptions);
		css3GridSetWidth(id);
	});
	if($(".css3_grid_slider").length)
		$(window).resize(function(){
			$(".p_table_sliding").each(function(){
				css3GridSetWidth($(this).attr("id"));
			});
		});
	$(".css3_grid_hidden_rows_control").click(function(event){
		event.preventDefault();
		var self = $(this);
		self.parent().find(".css3_grid_hidden_row").toggleClass("css3_grid_hide");
		$(this).children(".css3_grid_hidden_rows_control_expand_text").toggleClass("css3_grid_hide");
		$(this).children(".css3_grid_hidden_rows_control_collapse_text").toggleClass("css3_grid_hide");
		if(self.parent().hasClass("p_table_sliding"))
		{
			var time = 250;
			var animationInterval = setInterval(function(){
				time--;
				css3GridSetWidth(self.parent().attr("id"));
				if(time==0)
					clearInterval(animationInterval);
			}, 1);
		}
	});
});