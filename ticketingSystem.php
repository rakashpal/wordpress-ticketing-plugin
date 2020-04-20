<?php
/*
Plugin Name:Ticketing System
Plugin URI: 
Description: 
Version:1.0
Author: Rakashpal Singh
Author URI: https://rakshpal.firebaseapp.com/
License: 
Text Domain: ticketingSystem
*/

//Plugin Constants  declared
define( 'TICKETING_SYSTEM_VERSION', '1.0' );
define( 'TICKETING_SYSTEM_MINIMUM_WP_VERSION', '4.1.4' );
define( 'TICKETING_SYSTEM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TICKETING_SYSTEM_DELETE_LIMIT', 100000 );
define( 'TICKETING_SYSTEM_PLUGIN_URL', plugin_dir_url( __FILE__ ));


register_activation_hook( __FILE__, array( 'Ticketing_System', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Ticketing_System', 'plugin_deactivation' ) );

require_once( TICKETING_SYSTEM_PLUGIN_DIR . 'class.ticketing-system.php' );
add_action( 'init', array( 'Ticketing_System', 'init' ) );

