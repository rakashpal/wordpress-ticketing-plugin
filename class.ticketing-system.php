<?php

class Ticketing_System{
	private static $temp_path='';
	private static $initiated = false;
	 
	/**
	* 
	* 
	* @return
	*/

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}
	
	
	
	/**
	 * Initializes WordPress hooks
	 */
	private static function init_hooks() {
		self::$initiated = true;
        add_action( 'wp_enqueue_scripts', array('Ticketing_System','load_web_styles' ));
        add_shortcode( 'ticket', array( 'Ticketing_System', 'ticket_short_code_func' ) );
        add_shortcode( 'view_ticket', array( 'Ticketing_System', 'view_ticket_short_code_func' ) );
        add_action('wp_ajax_save_ticket', array('Ticketing_System','save_ticket'));
        
        add_action('wp_ajax_save_ticket_revert', array('Ticketing_System','save_ticket_revert_func'));
        add_action('wp_ajax_complete_ticket', array('Ticketing_System','complete_ticket_func'));

        

	 }
	
     public static function ticket_short_code_func( $atts, $content = "" ) {
        $atts = shortcode_atts( array(
            'name' => 'no foo',
        ), $atts, 'ticket' );
        ob_start();
        require( TICKETING_SYSTEM_PLUGIN_DIR . '/templates/tbl_ticket.php' );  

        return ob_get_clean();
    }

    public static function save_ticket(){
       
        extract($_POST);
        global $wpdb;
        $table_name = $wpdb->prefix .'tickets'; 

        $data = array(
            'user_id'     => get_current_user_id(),
            'name'    => $name,
            'phone'=>$phone,
            'issue' =>$issue,
            'description'=>$description,
            'status'   => "pending",
            'ticket_date'      => time()
        );  
        $format = array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        );
        
$success=$wpdb->insert( $table_name, $data, $format );
// Print last SQL query string
//var_dump($wpdb->last_query);

// Print last SQL query result
//var_dump($wpdb->last_result);

// Print last SQL query Error
//var_dump($wpdb->last_error);
if(success){
    $adminEmail=get_option( 'admin_email' );
    $to = $adminEmail;
$subject = 'Ticket:'.$issue;
$body = $description;
$headers = array('Content-Type: text/html; charset=UTF-8');
 
wp_mail( $to, $subject, $body, $headers );
    echo json_encode(['success'=>1,'message'=>'Ticket Created successfully.']);
}else{
    echo json_encode(['success'=>0,'message'=>'Error in creating ticket.Please try again']);
}
die;
    }

    public static function complete_ticket_func(){
        extract($_POST);
        global $wpdb;
        $table_name = $wpdb->prefix .'tickets'; 

        $data = array(
            'status'   => "completed"
        );  
        $match=array('ticket_id' => $ticket_id);
        $format = array(
            '%s'
        );
        $matchfor=array('%d');
        
$success=$wpdb->update( $table_name, $data,$match, $format,$matchfor );
// Print last SQL query string
//var_dump($wpdb->last_query);

// Print last SQL query result
//var_dump($wpdb->last_result);

// Print last SQL query Error
//var_dump($wpdb->last_error);
if(success){
    $adminEmail=get_option( 'admin_email' );
    $result = $wpdb->get_results("SELECT t.*,u.user_email,u.display_name FROM  $table_name2 t join  $table_name1 u on u.ID=t.user_id where ticket_id=".$ticket_id);
    if (!current_user_can('administrator')){
    $adminEmail=result[0]->user_email;
    }
   
    $to = $adminEmail;
$subject = 'Ticket:'.$issue;
$body = "Task status marked completed" ;
$headers = array('Content-Type: text/html; charset=UTF-8');
 
wp_mail( $to, $subject, $body, $headers );
    echo json_encode(['success'=>1,'message'=>'Task status marked completed']);
}else{
    echo json_encode(['success'=>0,'message'=>'Error in marking completed ticket.Please try again']);
}
die;
    }
    
    public static function save_ticket_revert_func(){
       
        extract($_POST);
        global $wpdb;
        $table_name = $wpdb->prefix .'ticketmeta'; 
        $table_name2 = $wpdb->prefix .'tickets'; 
        $table_name1 = $wpdb->prefix .'users'; 

        $data = array(
            'user_id'     => get_current_user_id(),
            'ticket_id'    => $ticket_id,
            'revert'=>$revert,
            'revert_date'      => time()
        );  
        $format = array(
            '%d',
            '%d',
            '%s',
            '%s'
        );
        
$success=$wpdb->insert( $table_name, $data, $format );
// Print last SQL query string
//var_dump($wpdb->last_query);

// Print last SQL query result
//var_dump($wpdb->last_result);

// Print last SQL query Error
//var_dump($wpdb->last_error);
if(success){
    $adminEmail=get_option( 'admin_email' );
     $result = $wpdb->get_results("SELECT t.*,u.user_email,u.display_name FROM  $table_name2 t join  $table_name1 u on u.ID=t.user_id where ticket_id=".$ticket_id);
    if (!current_user_can('administrator')){
       
        $adminEmail=result[0]->user_email;
        }
    $to = $adminEmail;
$subject = "Ticket Revert:".$result[0]->issue;
$body = $revert;
$headers = array('Content-Type: text/html; charset=UTF-8');
 
wp_mail( $to, $subject, $body, $headers );
    echo json_encode(['success'=>1,'message'=>'Ticket revert send  successfully.']);
}else{
    echo json_encode(['success'=>0,'message'=>'Error in sending ticket.Please try again']);
}
die;
    }
    
    public static function view_ticket_short_code_func( $atts, $content = "" ) {
        // $atts = shortcode_atts( array(
        //     'name' => 'no foo',
        // ), $atts, 'ticket' );
        global $wpdb;
        $table_name = $wpdb->prefix .'tickets'; 
      $table_name1 = $wpdb->prefix .'users'; 
      $table_name2 = $wpdb->prefix .'ticketmeta'; 
      $current_user_id=get_current_user_id();
      
       
        ob_start();
        if(isset($_GET['ticket_id']) && !empty($_GET['ticket_id'])){
            $result = $wpdb->get_results("SELECT t.*,u.user_email,u.display_name FROM  $table_name t join  $table_name1 u on u.ID=t.user_id where ticket_id=".$_GET['ticket_id']);

            // $resultrevert = $wpdb->get_results("SELECT t.*,u.user_email,u.display_name FROM  $table_name2 t join  $table_name1 u on u.ID=t.user_id where ticket_id=".$_GET['ticket_id']." order by t.revert_date");
            
            $resultrevert = $wpdb->get_results("SELECT * FROM  $table_name2  where ticket_id=".$_GET['ticket_id']." order by revert_date desc");
            
            require( TICKETING_SYSTEM_PLUGIN_DIR . '/templates/tbl_view_single_ticket.php' );  

        }else{
            $where='';
         
            if (!current_user_can('administrator')){
                $where='where user_id='. $current_user_id;
            }
        $results = $wpdb->get_results("SELECT t.*,u.user_email,u.display_name FROM  $table_name t join  $table_name1 u on u.ID=t.user_id $where");
        require( TICKETING_SYSTEM_PLUGIN_DIR . '/templates/tbl_view_ticket.php' );  
        }

        return ob_get_clean();
    }
    /**
	 * 
	 * 
	 * @return
	 */
	 public static function load_web_styles(){
		wp_enqueue_style('web-styles', TICKETING_SYSTEM_PLUGIN_URL.'/css/style.css');
		wp_enqueue_script('my_custom_script', TICKETING_SYSTEM_PLUGIN_URL . 'js/custom.js',array( 'jquery' ));	
     }
     

	/**
	 * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
	 * @static
	 */
	public static function plugin_activation() {
		if ( version_compare( $GLOBALS['wp_version'], TICKETING_SYSTEM_MINIMUM_WP_VERSION, '<' ) ) {
			load_plugin_textdomain( 'ticketingSystem' );
			
			$message = 'This plugin is not compatible with current wordpress version.Please update Current Wordrpess Version';

			TagFilter::bail_on_activation( $message );
		}
		
		global $wpdb;
     $charset_collate = $wpdb->get_charset_collate();
     $table_name = $wpdb->prefix . 'tickets';
     $table_name1= $wpdb->prefix. 'ticketmeta';

    #Check to see if the table exists already, if not, then create it

    if($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) 
    {

        $sql = "CREATE TABLE $table_name (
		ticket_id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
		name varchar(255)   NOT NULL,
        phone varchar(255) null,
		issue varchar(100)  NULL,
        description  longtext null,
		status varchar(10) NULL,
        ticket_date varchar(255) NULL,
		PRIMARY KEY (ticket_id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
    }
		if($wpdb->get_var( "show tables like '$table_name1'" ) != $table_name1) 
    {
		$sql1="CREATE TABLE $table_name1 (
		meta_id mediumint(9) NOT NULL AUTO_INCREMENT,
        ticket_id int(11) NOT NULL,
        user_id int(11) NOT NULL,
        revert  longtext,
        revert_date varchar(255) NULL,
        PRIMARY KEY (meta_id)
        )$charset_collate;";


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql1 );
		}
	}

	/**
	 * Removes all connection options
	 * @static
	 */
	public static function plugin_deactivation( ) {
		
	}
	
	
		private static function bail_on_activation( $message, $deactivate = true ) {
?>
<!doctype html>
<html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<style>
* {
	text-align: center;
	margin: 0;
	padding: 0;
	font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
}
p {
	margin-top: 1em;
	font-size: 18px;
}
</style>
<body>
<p><?php echo esc_html( $message ); ?></p>
</body>
</html>
<?php
		exit;
	}
	
	
}	