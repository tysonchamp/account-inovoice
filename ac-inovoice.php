<?php  # -*- coding: utf-8 -*-
/**
 * Plugin Name: Online Inovoice
 * Description: Custom plugin for managing online inovoices registered by visitors
 * Plugin URI:  https://github.com/tysonchamp/
 * Version:     1.0
 * Author:      Tyson
 * Author URI:  http://www.tysonchamp.com
 * Licence:     Open Source
 */
 

// creating tables on plugin activation
global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'inovoice_db';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		sc VARCHAR(255) NOT NULL,
		name VARCHAR(255) NOT NULL,
		email VARCHAR(255) NOT NULL,
		phone VARCHAR(255) NOT NULL,
		inovoices VARCHAR(255) NOT NULL,
		address VARCHAR(255) NOT NULL
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}
// run the install scripts upon plugin activation
register_activation_hook( __FILE__, 'jal_install' );

function fixed_db_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'fixed_db';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		sc VARCHAR(255) NOT NULL,
		inovoices VARCHAR(255) NOT NULL
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}
// run the install scripts upon plugin activation
register_activation_hook( __FILE__, 'fixed_db_install' );


/*
 * Install Plugin Fixed Datas in mysql DB
 * after plugin activation
 */
function jal_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'custom_posts';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}
// run the install scripts upon plugin activation
register_activation_hook( __FILE__, 'jal_install' );


/*
 * Sortcode functions for form goes here
 */
function form_function() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'inovoice_db';

	if (isset($_POST['inv_sub'])) {
		$sc = $_POST['sc'];
		$name = $_POST['cname'];
		$email = $_POST['cemail'];
		$phone = $_POST['cphone'];
		$address = $_POST['caddress'];
		$inovoices = "";
		$subject = "Your Inovoice!!";
		$msg = "Your inovoice is " . $inovoices;

		wp_mail($email, $subject, $msg);
		$wpdb->query($wpdb->prepare( "INSERT into $table_name VALUES(NULL, '$sc', '$name', '$email', '$phone', '$inovoices', '$address')", "" ));
	}
?>

<form action="" method="POST" role="form">
  <div class="form-group">
    <label for="email">Share Capital Amount:</label>
    <select name="sc" class="selectpicker">
	  <option value="1000000.00">1000000.00</option>
	  <option value="2000000.00">2000000.00</option>
	  <option value="3000000.00">3000000.00</option>
	  <option value="4000000.00">4000000.00</option>
	  <option value="5000000.00">5000000.00</option>
	  <option value="6000000.00">6000000.00</option>
	  <option value="7000000.00">7000000.00</option>
	  <option value="8000000.00">8000000.00</option>
	  <option value="9000000.00">9000000.00</option>
	  <option value="10000000.00">10000000.00</option>
	  <option value="11000000.00">11000000.00</option>
	  <option value="12000000.00">12000000.00</option>
	  <option value="13000000.00">13000000.00</option>
	  <option value="14000000.00">14000000.00</option>
	  <option value="15000000.00">15000000.00</option>
	  <option value="16000000.00">16000000.00</option>
	  <option value="17000000.00">17000000.00</option>
	  <option value="18000000.00">18000000.00</option>
	  <option value="19000000.00">19000000.00</option>
	  <option value="2000000.00">2000000.00</option>
	  <option value="21000000.00">21000000.00</option>
	  <option value="22000000.00">22000000.00</option>
	</select>
  </div>
  <div class="form-group">
    <label for="email">Full Name:</label>
    <input type="text" name="cname" class="form-control" id="email">
  </div>
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" name="cemail" class="form-control" id="email">
  </div>
  <div class="form-group">
    <label for="pwd">Your GSM Number:</label>
    <input type="text" name="cphone" class="form-control" id="pwd">
  </div>
  <div class="form-group">
    <label for="email">Your Address:</label>
    <textarea type="text" name="caddress" class="form-control" id="email">Your Full Address</textarea>
  </div>
  <button type="submit" name="inv_sub" class="btn btn-default">Submit</button>
</form>

<?php
}
add_shortcode('form-account', 'form_function');


/* call our code on admin pages only, not on front end requests or during
 * AJAX calls.
 * Always wait for the last possible hook to start your code.
 */
add_action( 'admin_menu', array ( 'custom_plugin', 'admin_menu' ) );

/**
 * Register three admin pages and add a stylesheet and a javascript to two of
 * them only.
 *
 * @author toscho
 *
 */
class custom_plugin
{
	/**
	 * Register the pages and the style and script loader callbacks.
	 *
	 * @wp-hook admin_menu
	 * @return  void
	 */
	public static function admin_menu()
	{
		// $main is now a slug named "toplevel_page_custom-plugin"
		// built with get_plugin_page_hookname( $menu_slug, '' )
		$main = add_menu_page(
			'Inovoices',                         // page title
			'Inovoices',                         // menu title
			// Change the capability to make the pages visible for other users.
			// See http://codex.wordpress.org/Roles_and_Capabilities
			'manage_options',                  // capability
			'custom-plugin',                         // menu slug
			array ( __CLASS__, 'add_post' ) // callback function
		);

		// $sub is now a slug named "custom-plugin_page_custom-plugin-sub"
		// built with get_plugin_page_hookname( $menu_slug, $parent_slug)
		$sub = add_submenu_page(
			'custom-plugin',                         // parent slug
			'Help',                     // page title
			'Help',                     // menu title
			'manage_options',                  // capability
			'help',                     // menu slug
			array ( __CLASS__, 'edit_posts' ) // callback function, same as above
		);

		/* See http://wordpress.stackexchange.com/a/49994/73 for the difference
		 * to "'admin_enqueue_scripts', $hook_suffix"
		 */
		foreach ( array ( $main, $sub ) as $slug )
		{
			// make sure the style callback is used on our page only
			add_action(
				"admin_print_styles-$slug",
				array ( __CLASS__, 'enqueue_style' )
			);
			// make sure the script callback is used on our page only
			add_action(
				"admin_print_scripts-$slug",
				array ( __CLASS__, 'enqueue_script' )
			);
		}

		// $text is now a slug named "custom-plugin_page_t5-text-included"
		// built with get_plugin_page_hookname( $menu_slug, $parent_slug)
		/*$text = add_submenu_page(
			'custom-plugin',                         // parent slug
			'Help',                     // page title
			'Help',                     // menu title
			'read',                  // capability
			'custom-plugin-help',                     // menu slug
			array ( __CLASS__, 'render_text_included' ) // callback function, same as above
		);*/
	}

	/**
	 * Print page output.
	 *
	 * @wp-hook toplevel_page_custom-plugin In wp-admin/admin.php do_action($page_hook).
	 * @wp-hook custom-plugin_page_custom-plugin-sub
	 * @return  void
	 */

	public static function add_post()
	{
		global $title;
		global $wpdb;

		print '<div class="wrap">';
		print "<h1>$title</h1>";
		
		if (isset($_POST['del_val'])) {
			$id = $_POST['id'];
			$wpdb->query("DELETE FROM {$wpdb->prefix}inovoice_db WHERE id='$id'");
		}

		$datas = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}inovoice_db");

	?>

        <table id="example1" class="table table-bordered table-striped">
	        <thead>
	        	<tr>
	            	<th>ID</th>
	            	<th>Full Name</th>
	            	<th>Share Capital</th>
	            	<th>Email</th>
	            	<th>Phone</th>
	            	<th>Address</th>
	            	<th>Inovoiced Amount</th>
	          	</tr>
	        </thead>
	        <tbody>
	        	<tr>
	        <?php foreach ($datas as $data) { ?>
	        		<td><?php echo $data->id; ?></td>
	            	<td><?php echo $data->sc; ?></td>
	            	<td><?php echo $data->name; ?></td>
	            	<td><?php echo $data->email; ?></td>
	            	<td><?php echo $data->phone; ?></td>
	            	<td><?php echo $data->address; ?></td>
	            	<td><?php echo $data->inovoices; ?></td>
	            	<td>
	            		<form action="" method="POST">
	            			<input type="hidden" name="data_id" value="<?php echo $data->id; ?>"></input>
	            			<input type="submit" name="del_val" value="Delete"></input>
	            		</form>
	            	</td>
	         <?php } ?>
	        	</tr>
	        </tbody>
	   
      	</table>
	<?php

		print '</div>';
	}


	public static function edit_posts()
	{
		print '<div class="wrap">';
		print "<h1>$title</h1>";
		// create a page name edit-post.php
		
		print '</div>';
	}

	/**
	 * Print included HTML file.
	 *
	 * @wp-hook custom-plugin_page_t5-text-included
	 * @return  void
	 */
	public static function render_text_included()
	{
		global $title;

		print '<div class="wrap">';
		print "<h1>$title</h1>";
		// create a page name readme.php
		//include('readme.php');
		
		print '</div>';
	}

	/**
	 * Load stylesheet on our admin page only.
	 *
	 * @return void
	 */
  public static function enqueue_style()
  {
    wp_register_style(
      'bootstrap_min_css',
      plugins_url( 'css/bootstrap.min.css', __FILE__ )
    );
    wp_enqueue_style( 'bootstrap_min_css' );
    wp_register_style(
      'bootstrap_datatable_css',
      plugins_url( 'css/dataTables.bootstrap.css', __FILE__ )
    );
    wp_enqueue_style( 'bootstrap_datatable_css' );
    wp_register_style(
      'plugin_style_css',
      plugins_url( 'css/pf_style.css', __FILE__ )
    );
    wp_enqueue_style( 'plugin_style_css' );
  }
  
  /**
   * Load JavaScript on our admin page only.
   *
   * @return void
   */
  public static function enqueue_script()
  {
    wp_register_script(
      'jquery_min_js',
      plugins_url( 'js/jQuery-2.1.4.min.js', __FILE__ ),
      array(),
      FALSE,
      TRUE
    );
    wp_enqueue_script( 'jquery_min_js' );
    wp_register_script(
      'bootstrap_min_js',
      plugins_url( 'js/bootstrap.min.js', __FILE__ ),
      array(),
      FALSE,
      TRUE
    );
    wp_enqueue_script( 'bootstrap_min_js' );
    wp_register_script(
      'jdatatable_min_js',
      plugins_url( 'js/jquery.dataTables.min.js', __FILE__ ),
      array(),
      FALSE,
      TRUE
    );
    wp_enqueue_script( 'jdatatable_min_js' );
    wp_register_script(
      'datatable_min_js',
      plugins_url( 'js/dataTables.bootstrap.min.js', __FILE__ ),
      array(),
      FALSE,
      TRUE
    );
    wp_enqueue_script( 'datatable_min_js' );
  }

}
