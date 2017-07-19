<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.datainterlock.com
 * @since      1.0.0
 *
 * @package    Note_Press
 * @subpackage Note_Press/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Note_Press
 * @subpackage Note_Press/admin
 * @author     Rod Kinnison <postmaster@datainterlock.com>
 */
class Note_Press_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Note_Press_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Note_Press_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/Note_Press-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Note_Press_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Note_Press_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/Note_Press-admin.js', array( 'jquery' ), $this->version, false );
	}
	
	public function Note_Press_dashboard( $post, $callback_args ) {
		global $wpdb;
		$userID = get_current_user_id();
		$table_name = $wpdb->prefix . "Note_Press";
		$num     = $wpdb->get_var("SELECT COUNT(*) FROM $table_name where userTo = $userID");
		if ($num == 0)
		{
			echo __('No notes to you.','Note_Press');
		}
		else
		{
			echo '<table width="100%">';
			echo '<tr><td></td><td>'.__("Title","Note_Press").'</td><td>'.__("Priority","Note_Press")."</td><td>".__("Deadline","Note_Press")."</td></tr>";
			$noteList = $wpdb->get_results("Select * from $table_name where userTo = $userID");
			foreach ($noteList as $note)
			{
					if ($note->Deadline == NULL)
					{
						$thisdate = '';
					}
					else
					{
						$date = new DateTime($note->Deadline);
						$thisdate =date_format($date,'Y-m-d');
					}
					echo '<tr><td>';
					echo ("<img src=".get_option("Note_Press_icons_url",'null').$note->Icon."  alt='Icon not found' >");
					echo '</td><td>';
					echo "<a href='?page=Note_Press-Main-Menu&action=view&id=$note->ID'>".$note->Title.'</a>';
					echo '</td><td>';
					switch ($note->Priority)
					{
						case 0: echo "<img src=".plugins_url('admin/images/P0.png', dirname(__FILE__))." alt='Icon not found'>";
					            break;
						case 1: echo "<img src=".plugins_url('admin/images/P1.png', dirname(__FILE__))." alt='Icon not found'>";
					            break;
						case 2: echo "<img src=".plugins_url('admin/images/P2.png', dirname(__FILE__))." alt='Icon not found'>";
					            break;
					}
					echo '</td><td>';
					echo $thisdate;
					/*
					switch ($note->userRead)
					{
						case 0: echo "<img src=".plugins_url('admin/images/X.png', dirname(__FILE__))." alt='Icon not found'>";
					            break;
						case 1: echo "<img src=".plugins_url('admin/images/CH.png', dirname(__FILE__))." alt='Icon not found'>";
					            break;
					}
					*/
					echo '</td></tr>';					
			}
			echo '</table>';
		}
	}	
		
	function Note_Press_add_dashboard_widgets() {
		add_meta_box(
		'dashboard_widget',
		__('Note Press - Notes to you','Note_Press'),
		array($this, 'Note_Press_dashboard'),
		'dashboard',
		'side',
		'high'
	);		
	}
	
	function Note_Press_add_plugin_admin_menu() {
		add_menu_page(__( 'Note Press', 'Note_Press' ), 'Note Press', 'manage_options', 'Note_Press-Main-Menu', array($this, 'Note_Press_load_menu'),  plugins_url('admin/images/Note_Pressicon.png', dirname(__FILE__)));
	}
	
	
	function Note_Press_load_menu()
	{
		include_once('Note_Press-admin-menu.php');
	}		
}
