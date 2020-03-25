<?php
/**
 * @link              http://nilaypatel.info
 * @since             1.0.0
 * @package           Easy_Google_Analytics_WP
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Google Analytics WP
 * Plugin URI:        http://nilaypatel.info
 * Description:       This plugin provides you to add google analytics from back end to the site. You just need to add Tracking ID or Full script into the back end and you are done. 
 * Version:           1.0.0
 * Author:            Nilay Patel
 * Author URI:        http://nilaypatel.info
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-google-analytics-wp
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The code that runs during plugin activation.
 */
register_activation_hook( __FILE__, 'egawp_activate' );

function egawp_activate() {
		update_option('egawp_activated_on',@date('d-m-Y h:i:s'));
}

function egawp_css_load() {
	   echo '<style type="text/css">
		   .egawp_input{ width:100%; }
		   .egawpcentrdata{ text-align:center; }
		 </style>';
}

add_action('admin_head', 'egawp_css_load');

/**
 * Register a menu page.
 */
function egawp_menu_page(){
    add_menu_page( 
        __( 'easy-google-analytics-wp', 'egawp' ),
        'Easy Google Aanlytic WP',
        'manage_options',
        'easy_google_analytics_wp',
        'easy_google_analytics_wp',
        plugins_url('/assets/images/egawp.png',__FILE__ ), 6
    ); 
}
add_action( 'admin_menu', 'egawp_menu_page' );

/* The code that runs when page loaded in back end */

function easy_google_analytics_wp(){?>
<h2>Easy Google Analytic WP</h2>
<p>You just need to add Google Analytics Tacking ID <strong><em>e.g. UA-00000000-0</em></strong> or add full script that you get from the google</p>
<p><strong>NOTE:</strong> You can leave empty fields incase you do not want to add GA code to site.</p>
<?php if(isset($_REQUEST['egawp_update']) && $_REQUEST['egawp_update'] == 1){ 
		echo esc_html( '<div class="notice notice-success is-dismissible"><p>Data saved successfully.</p></div><br/>');
 }?>

<script type="text/javascript">
jQuery(document).ready(function(e) {
	jQuery("#egawp_submit").click(function(e) {
		var egawp_gacode_only = jQuery.trim(jQuery("#egawp_gacode_only").val());	
		var egawp_gacode_script = jQuery.trim(jQuery("#egawp_gacode_script").val());
		if(!egawp_gacode_only || !egawp_gacode_script){
			jQuery.ajax({
				 type : "post",
				 url : '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
				 data : jQuery('form#egawp_form').serialize(),
				 success: function(resp) {
					 if(resp == 'done'){
						 location.href = '<?php echo esc_url(admin_url('admin.php?page=easy_google_analytics_wp&egawp_update=1')); ?>';
					 }
				 }
			});
		}else{
			alert("Add only one type of value");
			return false;
		}
	});
});
</script>
<form name="egawp_form" id="egawp_form" method="post">
<input type="hidden" name="action" value="egawp_save_data" />
<input type="hidden" name="nonce" value="<?php _e(wp_create_nonce("egawp_save_data")); ?>" />
<table class="wp-list-table widefat fixed striped pages">
	<tr>
    	<td width="200">Add Google Analytic Code Only <br/> <em>e.g. UA-00000000-0</em></td>
        <td><input type="text" name="egawp_gacode_only" id="egawp_gacode_only" placeholder="e.g. UA-00000000-0" class="egawp_input" value="<?php if(get_option('egawp_select_type') == 1){ echo esc_html(get_option('egawp_value')); } ?>"  /></td>
    </tr>
   	<tr class="egawpcentrdata">
    	<td colspan="2">Or</td>
    </tr>
	<tr>
    	<td width="200">Add Full Script of <br/> Google Analytic Code</td>
        <td><textarea name="egawp_gacode_script" rows="5" class="egawp_input" id="egawp_gacode_script" placeholder="e.g. <script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-00000000-0', 'auto');
ga('send', 'pageview');
</script>"><?php if(get_option('egawp_select_type') == 2){ echo stripslashes(get_option('egawp_value')); } ?></textarea></td>
    </tr>
    <tr>
    	<td><input type="button" name="egawp_submit" id="egawp_submit" class="button-primary" value="Save" /></td>
        <td>&nbsp;</td>
    </tr>
</table>	
</form>
<?php
}


/**
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook( __FILE__, 'egawp_deactivate' );

function egawp_deactivate() {
	update_option('egawp_deactivated_on',@date('d-m-Y h:i:s'));
}

/* Load Values to front end */
function egawp_load_values_frontside(){
	if(get_option('egawp_select_type') == 1){
	?>
    <script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', '<?php echo esc_html(get_option('egawp_value')); ?>', 'auto');
		ga('send', 'pageview');
	</script>
    <?php	
	}elseif(get_option('egawp_select_type') == 2){
		echo stripslashes(get_option('egawp_value'));
	}
}
if(get_option('egawp_select_type') != '' && get_option('egawp_value') != ''){
	add_action('wp_head', 'egawp_load_values_frontside');
}


/* Save Data */
add_action("wp_ajax_egawp_save_data", "egawp_save_form_data");
function egawp_save_form_data() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "egawp_save_data")) { //egawp_nonce_data  egawp_save_data
      exit("You are not supposed to be here.");
   }else {
	  
	  $egawp_gacode_only = sanitize_text_field($_REQUEST['egawp_gacode_only']);
	  $egawp_gacode_script = ($_REQUEST['egawp_gacode_script']);
	  
	  if(!empty(trim($egawp_gacode_only))){
	 	update_option('egawp_select_type','1');
		update_option('egawp_value',$egawp_gacode_only);
   	  }elseif(!empty(trim($egawp_gacode_script))){
		update_option('egawp_select_type','2');
		update_option('egawp_value',$egawp_gacode_script); 
	  }else{
		update_option('egawp_select_type','0');
		update_option('egawp_value',''); 
	  }
	  echo esc_html('done');
   }
   die();
}