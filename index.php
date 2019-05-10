<?php
 
/*
Plugin Name: Alert Box
Plugin URI: https://furqanhussain.com/
Description: ''
Author: Furqan Hussain
Version: 1.7.2
Author URI: https://furqanhussain.com/
*/

 
function setting() { 
    add_option( 'alertbox', 'Furqan Hussain.');
    register_setting( 'plugin_option', 'alertbox', 'myplugin_callback' );
    register_setting( 'plugin_option', 'posts', 'myplugin_callback' );
    register_setting( 'plugin_option', 'pages', 'myplugin_callback' );
    register_setting( 'plugin_option', 'postId', 'myplugin_callback' );
}
 add_action( 'admin_init', 'setting' );

function page() {
    add_options_page('Alert Box Page', 'Alert Box', 'manage_options', 'myplugin', 'index');
    wp_register_script( 'js_script', plugins_url('script.js', __FILE__), array('jquery'));
    wp_enqueue_script( 'js_script' ); 
    wp_localize_script( 'js_script', 'ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'baseurl' => get_site_url() ) 
    ); 
}
add_action('admin_menu', 'page');

add_action( 'wp_ajax_my_action', 'my_action' );
function my_action() {
	global $wpdb;
	$postId = intval( $_POST['postId'] ); 
    echo $postId; 
	wp_die();
}

function load_my_scripts() {
    global $post;
    $id = explode(",",get_option('postId')); 
    if(is_single($id) or is_page($id)){
        wp_enqueue_script('script', plugins_url('script.js', __FILE__)  );
        wp_localize_script( 'script', 'ajax_object',
            array("msg"=> get_option('alertbox'), 'baseurl' => get_site_url() )     
        ); 
    }

}
add_action('wp_enqueue_scripts', 'load_my_scripts');
  
function index() { 
?>
  <div>
  <?php screen_icon(); ?>
  <h2>Alertbox</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'plugin_option' ); ?> 
  <b>Alert Message</b> 
  <input type="text" id="alertbox" name="alertbox" value="<?php echo get_option('alertbox'); ?>" /> 
  <br/><br/>
  <p> Checked the pages/post you want to show alert.</p>
  <input type="checkbox" id="posts" onChange="get_list(this)" name="posts"  <?php echo get_option('posts')=='on'? 'checked':''; ?> />Post
  <input type="checkbox" id="pages" onChange="get_list(this)" name="pages" <?php echo get_option('pages')=='on'? 'checked':''; ?> />Pages
  <ol id="list">
  <?php 
  $type = array();
  if(get_option('pages')=='on'){
    $type[] = 'page';
  }
  if(get_option('posts')=='on'){
    $type[] = 'post';
  }   
  $postId = explode(',', get_option('postId') ); 
    $args = array(
        'post_type' => $type  ,
        'post_status' => 'publish'
        );
        $loop = new WP_Query($args);
        while($loop->have_posts()): $loop->the_post();  
                ?>
                <li class="<?php echo get_post_type(); ?>s"><input type="checkbox" onclick="a(this)" class="post" value="<?php echo get_the_ID(); ?>" name="postId" <?php if( in_array(get_the_ID() , $postId )){echo 'checked';} ?>/><?php echo get_the_title(); ?></li>  
 
                <?php  
                $last_post_type = $currpost_type;
    endwhile;
     //print_r( $loop ); 
    // foreach($pages as $i){
    //     print_r($i  );
    // }
?>
  </ol>
  <input type="hidden" name="postId" id="postId" value="<?php echo get_option('postId'); ?>"/> 
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
} 

 
?>

 