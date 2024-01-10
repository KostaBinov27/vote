<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://linkedin.com/in/kosta-binov
 * @since      1.0.0
 *
 * @package    Votes
 * @subpackage Votes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Votes
 * @subpackage Votes/public
 * @author     Kosta <kostabinovps@gmail.com>
 */
class Votes_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Votes_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Votes_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/votes-public.css', array(), $this->version, 'all' );

	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/votes-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Display the voting after the content
	 *
	 * @since    1.0.0
	 */
	
	public function show_voting( $content ) {
		if( !is_home() || !is_front_page()){
			ob_start();
    			include plugin_dir_path(__FILE__).'partials/votes-public-display.php';
			$vote = ob_get_clean();
			
			$content = $content.$vote;
		}
		
		return $content;
	}

	/**
	 * Main function where users are voting, storing the data in meta fields for the posts
	 *
	 * @since    1.0.0
	 */
	public function user_vote() {
		$votedIp = $_COOKIE['userVotedIP'];
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "user_vote_nonce") || $votedIp == $this->getIPAddress()) {
		   exit("Code is a poetry!");
		}   
		
		$vote_yes_no = esc_attr($_REQUEST["vote"]); 
		$vote_count = get_post_meta(esc_attr($_REQUEST["post_id"]), $vote_yes_no."_votes", true);

		$vote_count = ($vote_count == '') ? 0 : $vote_count;
		$new_vote_count = $vote_count + 1;
	 
		$vote = update_post_meta(esc_attr($_REQUEST["post_id"]), $vote_yes_no."_votes", $new_vote_count);
	 
		if($vote === false) {
		   $result['type'] = "error";
		}
		else {
		   $result['type'] = "success";

		   update_post_meta(esc_attr($_REQUEST["post_id"]), "voted_ip", $this->getIPAddress());
		   update_post_meta(esc_attr($_REQUEST["post_id"]), "voted_answer", $_REQUEST["vote"]);

		   $rez = $this->get_percentage($_REQUEST);

		   $result['vote_percentage_yes'] = number_format($rez['yes'], 1);
		   $result['vote_percentage_no'] = number_format($rez['no'], 1);
		}
	 
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		   $result = json_encode($result);
		   echo $result;
		}
		else {
		   header("Location: ".$_SERVER["HTTP_REFERER"]);
		}

		die();
	}
	
	/**
	 * Getting percentage of the votes
	 *
	 * @since    1.0.0
	 */
	public function get_percentage($data) {
		$votes_yes = get_post_meta(esc_attr($data["post_id"]), "yes_votes", true);
		$votes_no = get_post_meta(esc_attr($data["post_id"]), "no_votes", true);
		$total_votes = intval($votes_yes) + intval($votes_no);
		$yes_percentage = ($total_votes > 0) ? (intval($votes_yes) / intval($total_votes)) * 100 : 0;
		$no_percentage = ($total_votes > 0) ? (intval($votes_no) / intval($total_votes)) * 100 : 0;

		$rezults = array('yes' => $yes_percentage, 'no' => $no_percentage);

		return $rezults;
	}

	/**
	 * Register the js script for the Ajax
	 *
	 * @since    1.0.0
	 */
	public function script_enqueuer() {
		wp_register_script( "my_voter_script", plugin_dir_url( __FILE__ ) . 'js/votes-ajax-public.js', array('jquery') );
		wp_localize_script( 'my_voter_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
	 
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'my_voter_script' );
	 
	}

	/**
	 * Getting the IP address of the visitor for restiriction purpose
	 *
	 * @since    1.0.0
	 */
	public function getIPAddress() {

		if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
				$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
				$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		}
		else {
			$ip = $remote;
		}

		return $ip;
	}

	public function detect_if_voted($postID){
		$disabled = false;
		$notVoted = false;
		$votedIp = $_COOKIE['userVotedIP'];
		if (!empty($_COOKIE["userVotedIP"]) && $votedIp == $this->getIPAddress()) {
			$notVoted = true;
			$data = array("post_id" => $postID);
			$percentages = $this->get_percentage($data);
			$votedValue = get_post_meta(esc_attr( $postID ), "voted_answer", true);
			$disabled = true;
		}
		
		$data = array ( 'disabled' => $disabled, 'voted' => $notVoted, 'percentages' => $percentages, 'voted_value' => $votedValue );

		return $data;
	}

}
