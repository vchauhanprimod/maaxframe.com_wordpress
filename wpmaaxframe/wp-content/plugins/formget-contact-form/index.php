<?php
/*
  Plugin Name: FormGet Contact Form
  Plugin URI: http://www.formget.com
  Description: FormGet Contact Form is an eassy and effective form builder tool which enable you to bulid and embed form on your website in few steps. With FormGet Contact Form manage all your contact forms and your entire client communication at one single place.
  Version: 3.6
  Author: FormGet
  Author URI: http://www.formget.com
 */

function my_admin_notice() {
    $fg_iframe_form = get_option('fg_embed_code');
    $string = "sideBar";
    $pos = strpos($fg_iframe_form, $string);
    if ($pos == false) {
        global $current_user;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
        if (!get_user_meta($user_id, 'admin_ignore_notice')) {
            ?>
            <div class="fg_trial-notify updated below-h2">
                <p class="fg_division_note">
                    Welcome to FormGet - You're almost ready to create your form <a class="fg_button_prime click_notice" href='<?php echo admin_url('admin.php?page=cf_page'); ?>'>Click to Create.</a><?php printf(__('<a class="fg_hide_notice fg_button_prime", href="%1$s">Hide Notice</a>'), '?admin_nag_ignore=0'); ?>
                </p>
            </div>
            <?php
        }
    }
}

if (!isset($_GET['page']) == 'cf_page') {
    add_action('admin_notices', 'my_admin_notice');
}

function admin_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET['admin_nag_ignore']) && '0' == $_GET['admin_nag_ignore']) {
        add_user_meta($user_id, 'admin_ignore_notice', 'true', true);
    }
}

add_action('admin_init', 'admin_nag_ignore');

function delete_user_entry() {
    global $current_user;
    $user_id = $current_user->ID;
    delete_user_meta($user_id, 'admin_ignore_notice', 'true', true);
}

register_deactivation_hook(__FILE__, 'delete_user_entry');


if (is_admin()) {

    function cf_add_style() {
        wp_enqueue_style('form1_style1_sheet1', plugins_url('css/fgstyle.css', __FILE__));
    }

    add_action("init", "cf_add_style");

    function wordpress_style() {
        wp_enqueue_style('stylesheet_menu', admin_url('load-styles.php?c=1&amp;dir=ltr&amp;load=admin-bar,wp-admin,buttons,wp-auth-check&amp'));
        wp_enqueue_style('style_menu', admin_url('css/colors-fresh.min.css'));
    }

    add_action('init', 'wordpress_style');
}

//setting page
add_action('admin_menu', 'cf_menu_page');

function cf_menu_page() {
    add_menu_page('cf', 'Contact Form', 'manage_options', 'cf_page', 'cf_setting_page', plugins_url('image/favicon.ico', __FILE__), 109);
}

function cf_setting_page() {
    $url = plugins_url();
    ?>

<!--    <div id="fg_videoContainer" >

    </div>-->
    <?php
    global $wpdb;
    $fg_video_code = get_option('fg_hide_video');
    if ($fg_video_code == "hide") {
        ?>
        <!--        <div class="fg_notice_div" id="fg_notice_div">
                    <p class="fg_video_notice" style="color:white;" > 
                        We have created a simple video for using the FormGet Plugin. 
                    <div class="fg_getting_started" id="fg_video_getting_started"><img src="<?php echo plugins_url('image/wvideo.png', __FILE__); ?>"></div>
                    <div class="hide_video_notice"><img src="<?php echo plugins_url('image/hide-btn-1.png', __FILE__); ?>"></div>
                </p>
        </div>-->

    <?php } ?>
    

    <div id="fg_of_container" class="fg_wrap" 


         <form id="fg_ofform" action="" method="POST">
            <div id="fg_header">
                <div class="fg_logo">
                    <h2> FormGet Contact Form</h2>
                </div>
                <a target="#">
                    <div class="fg_icon-option"> </div>
                </a>
                <div class="clear"></div>
            </div>
            <div id="fg_main">

                <div id="fg_of-nav" class="wrap">
                    <h2 class="nav-tab-wrapper">

                        <span id="form_tab" class="nav-tab nav-tab-active ">Contact Form Builder </span>
                        <span id="embed_tab" class="nav-tab ">Embed Code</span>
                        <span id="help_tab" class="nav-tab  ">Help</span>
                        <span id="gopro_tab" class="nav-tab  ">Pricing Plan</span>
                        <span id="support_tab" class="nav-tab ">Support</span>

                    </h2>
                </div>
                <div id="fg_content">
                    <div class="fg_group" id="pn_content">


                        <div class="fg_section section-text">
                            <h3 class="fg_heading"> Create your custom form by just clicking the fields on left side of the panel. And then paste the form code in embed code section.</h3>
                            <div class="outer_iframe_div" id="outer_iframe_div">
                                <div class="inner_iframe_div" id="inner_iframe_div" >
                                    <iframe src="http://www.formget.com/app" name="iframe" id="iframebox" style="width:100%; height:900px; border:1px solid #dfdfdf;  align:center; overflow-y:scroll;" >
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>	
                    <div class="fg_group" id="pn_displaysetting">

                        <div class="fg_section section-text">
                            <h3 class="fg_heading">Embed code field will only accept code for tabbed widgets. Please do not place shortcode here. </h3>
                            <div class="option">
                                <div class="fg_controls" style="height:auto; padding-bottom: 10px;">
                                    <textarea name="content[html]" cols="60" rows="10"   class="regular-text"  id="fg_content_html" style="width:900px"><?php echo embeded_code(); ?></textarea> <br>
                                    <p class="fg_info"><b>Select the page in which you want to show "Contact Us" tab. By default it is visible on every page.</b><p>
                                        <?php
                                        $page_title = $wpdb->get_results("SELECT post_title, id FROM $wpdb->posts WHERE (post_type = 'page')");

                                        foreach ($page_title as $title) {
                                            $fg_list_page_id = get_option('fg_checked_page_id');
                                            ?>
                                            <input id="fg_selected_box" type="checkbox" name="checkbox" value="<?php echo $title->id; ?>" 
                                            <?php
                                            if (!empty($fg_list_page_id)) {
                                                foreach ($fg_list_page_id as $list) {
                                                    if ($list == $title->id) {
                                                        ?>
                                                               checked
                                                               <?php
                                                           }
                                                       }
                                                   }
                                                   ?> >

                                            <?php
                                            echo $title->post_title;
                                            echo "<br>";
                                        }
                                        ?>
                                    <div id="submit-form" class="fg_embed_code_save " > Save </div>			
                                    <div id="loader_img" align="center" style="margin-left:460px; display:none;">
                                        <img src="<?php echo plugins_url('image/ajax-loader.gif', __FILE__); ?>">
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="fg_group" id="pn_template">
                        <div class="fg_section section-text">
                            <h3 class="fg_heading"> Watch video tutorial to use Formget Contact Form Plugin</h3>
                            <div id="help_txt">
                                <iframe class="video_tobe_shown" width="700" height="400" src="//player.vimeo.com/video/84023688?autoplay=0&amp;loop=1&amp;rel=0&amp;wmode=transparent" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" __idm_id__="7182340"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="fg_group" id="pn_gopro">
                        <div class="fg_section section-text">
                            <h3 class="fg_heading"> Pricing Plan for all business types </h3>
                            <iframe src="http://www.formget.com/app/pricing" name="iframe" style="width:1030px; height:900px; overflow-y:scroll;" >
                            </iframe>
                        </div>
                    </div>
                    <div class="fg_group" id="pn_contactus">
                        <div class="fg_section section-text">
                            <h3 class="fg_heading"> Contact Us</h3>
                            <iframe class="formget_contact_form"  height='570' allowTransparency='true' frameborder='0' scrolling='no' style='width:100%;border:none'  src='http://www.formget.com/app/embed/form/qQvs-639'>Your Contact </iframe>
                        </div>
                    </div>	


                </div>
                <div class="clear"></div>
            </div>

            <div class="fg_save_bar_top">



            </div>

        </form>
    </div>

    <?php
}

function cf_embeded_script() {
    wp_enqueue_script('embeded_script', plugins_url('js/fg_script.js', __FILE__), array('jquery'));
    wp_localize_script('embeded_script', 'script_call', array('ajaxurl' => admin_url('admin-ajax.php')));
}

if (isset($_GET['page']) == 'cf_page') {
    add_action('init', 'cf_embeded_script');
}

function cf_text_ajax_process_request() {
    $text_value = $_POST['value'];
    $val = $_POST['value_hide'];
    $page_id = $_POST['page_id'];
    //echo $val;
    update_option('fg_hide_video', $val);
    update_option('fg_embed_code', $text_value);
    update_option('fg_checked_page_id', $page_id);
    //print_r($page_id);
    echo 1;
    die();
}

add_action('wp_ajax_master_response', 'cf_text_ajax_process_request');
add_action('wp_ajax_nopriv_master_response', 'cf_text_ajax_process_request');
if (!function_exists('embeded_code')) {

    function embeded_code() {
        //$title = get_the_title();
        $postid = get_the_ID();
        $fg_list_page_id = get_option('fg_checked_page_id');
        global $wpdb;
        $fg_iframe_form = get_option('fg_embed_code');
        $string = "sideBar";
        $pos = strpos($fg_iframe_form, $string);
        if (!empty($fg_list_page_id)) {
            foreach ($fg_list_page_id as $key) {
                if ($postid == $key) {
                    if ($pos == true) {
                        echo stripslashes($fg_iframe_form);
                    }
                } else {
                    if (is_front_page()) {
                        if ($pos == true) {
                            echo stripslashes($fg_iframe_form);
                        }
                    }
                }
            }
        } else {
            if ($pos == true) {
                echo stripslashes($fg_iframe_form);
            }
        }
    }
}

if (!is_admin()) {
    add_action('wp_head', 'embeded_code');
}
//schort code function
if (!function_exists('formget_shortcode')) {

    function formget_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
            'user' => '',
            'formcode' => '',
            'allowTransparency' => true,
            'height' => '500',
            'tab' => ''
                        ), $atts));
        $iframe_formget = '';
        $url = "http://www.formget.com/app/embed/form/" . $formcode;
        if ($tab == 'page') {
            $iframe_formget .="<iframe height='" . $height . "' allowTransparency='true' frameborder='0' scrolling='no' style='width:100%;border:none'  src='" . $url . "' >";
            $iframe_formget .="</iframe>";
            add_filter('widget_text', 'do_shortcode');
            return $iframe_formget;
        }
        if ($tab == 'tabbed') {
            $tabbed_formget = <<<EOD
<script type="text/javascript">
    var sideBar;
    (function(d, t) {
        var s = d.createElement(t),
                options = {
            'tabKey': '{$formcode}',
            'tabtext':'Contact Us',
            'height': '{$height}',
            'position': "",
            'textColor': 'ffffff',
            'tabColor': '7d9f2b',
            'fontSize': '16',
        };
        s.src = 'http://www.formget.com/app/app_data/user_js/widget.js';
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs)
                if (rs != 'complete')
                    if (rs != 'loaded')
                        return;
            try {
                sideBar = new buildTabbed();
                sideBar.initializeOption(options);
                sideBar.loadContent();
                sideBar.buildHtml();
            } catch (e) {
            }
        };
        var scr = d.getElementsByTagName(t)[0], par = scr.parentNode;
        par.insertBefore(s, scr);
    })(document, 'script');
</script>
EOD;
            return $tabbed_formget;
        }
    }

}
add_shortcode('formget', 'formget_shortcode');
?>