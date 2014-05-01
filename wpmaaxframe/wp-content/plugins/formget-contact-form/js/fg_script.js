jQuery(document).ready(function() {
    jQuery('.fg_group').hide();
    jQuery('#pn_content').css("display", "block");

    jQuery('#embed_tab').click(function() {
        // alert("hello");
        jQuery('.fg_group').hide();
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active');
        jQuery('#pn_displaysetting').css("display", "block");
        jQuery(".fg_embed_code_save").css("display", "block");

    });

    jQuery('#support_tab').click(function() {
        // alert("hello");
        jQuery('.fg_group').hide();
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active');
        jQuery('#pn_contactus').css("display", "block");
    });
    jQuery('#help_tab').click(function() {
        // alert("hello");
        jQuery('.fg_group').hide();
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active');
        jQuery('#pn_template').css("display", "block");
    });
    jQuery('#video_tab').click(function() {
        // alert("hello");
        jQuery('.fg_group').hide();
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active');
        jQuery('#pn_tutorial').css("display", "block");
    });

    jQuery('#gopro_tab').click(function() {
        // alert("hello");
        jQuery('.fg_group').hide();
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active');
        jQuery('#pn_gopro').css("display", "block");
    });

    jQuery('#button_to_upgrade').click(function() {
        // alert("hello");
        //window.location.href = "http://www.formget.com/app/pricing";
        //var win = window.open("http://www.formget.com/app/pricing", '_blank');
        //win.focus();
        //jQuery('.fg_group').hide();
        //jQuery('.nav-tab').removeClass('nav-tab-active');
        //jQuery('#gopro_tab').addClass('nav-tab-active');
        //jQuery('#pn_gopro').css("display", "block");
    });

    jQuery('#form_tab').click(function() {
        //  alert("hello");
        jQuery('.fg_group').hide();
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(this).addClass('nav-tab-active');
        jQuery('#pn_content').css("display", "block");
    });


    jQuery('.fg_embed_code_save').click(function() {
        jQuery('div#loader_img').css("display", "block");
        var text_value = jQuery('textarea#fg_content_html').val();
        var checkedValues = jQuery('input:checkbox:checked').map(function() {
            return this.value;
        }).get();
//alert(checkedValues);

        var data = {
            action: 'master_response',
            value: text_value,
            page_id: checkedValues
        };
        jQuery.post(script_call.ajaxurl, data, function(response) {
            if (response) {
                jQuery('div#loader_img').css("display", "none");
                // alert(response);
            }
            else {

                jQuery('div#loader_img').css("display", "none");
            }
        });
    });

    jQuery('div#fg_video_getting_started').click(function() {
        //jQuery('div#fg_videoContainer').css({"display": "block"});
        //autoPlayVideo('84023688', '700', '400');
    });


    function autoPlayVideo(vcode, width, height) {
        "use strict";
        jQuery("#fg_videoContainer").html('<iframe class="video_tobe_shown" width="' + width + '" height="' + height + '" src="//player.vimeo.com/video/' + vcode + '?autoplay=1&loop=1&rel=0&wmode=transparent" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
    }

    jQuery('div#fg_videoContainer').click(function() {
        jQuery('div#fg_videoContainer').css({"display": "none"});
        jQuery('div#fg_videoContainer').empty();
    });
    //jQuery("div.")
    jQuery(".hide_video_notice").click(function() {
        //alert("hello");
        var hide_data = {
            action: 'master_response',
            value_hide: "hide"
        };
        jQuery.post(script_call.ajaxurl, hide_data, function(response) {
            if (response) {
                //alert(response); 
                jQuery('.fg_notice_div').hide();
            }
            else {


            }
        });
    });
});