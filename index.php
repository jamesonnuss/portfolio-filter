<?php
/*
Plugin URI: http://www.jamesonnuss.com
Version: 1.0
Description: Portfolio Filter is a versatile plugin designed to efficiently create a Portfolio Gallery. Portfolio Filter breaks apart your work into different categories, making it easier to navigate. Impress potential clients with a clean portfolio site that truly shows off your work in an organized fashion. Portfolio Filter can also be used to create a simple image gallery that contains multiple image categories. For those who are in need of a fast, light and useful plugin that makes your portfolio come to life. Try out the free plugin today and see how easy it is to create a functional, dynamic portfolio. 
Plugin Name: Portfolio Filter
Author: Jameson Nuss
Author URI: http://www.jamesonnuss.com
License: GPLV2
*/

/* add_action function so that when WordPress begins to load our function will be called */
add_action('init', 'jcn_project_custom_init');      
      
/* START jcn_project_custom_init */
/* Adding the code that registers a Custom Post Type*/
function jcn_project_custom_init()  
{  
   $labels = array(
		'name'               => _x( 'Projects', 'post type general name' ),
		'singular_name'      => _x( 'Project', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'project' ),
		'add_new_item'       => __( 'Add New Project' ),
		'edit_item'          => __( 'Edit Project' ),
		'new_item'           => __( 'New Project' ),
		'all_items'          => __( 'All Projects' ),
		'view_item'          => __( 'View Projects' ),
		'search_items'       => __( 'Search Projects' ),
		'not_found'          => __( 'No projects found' ),
		'not_found_in_trash' => __( 'No projects found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Portfolio Filter'
	);
	$args = array(
		'labels'        	 => $labels,
		'description'   	 => 'Contains projects and project specific data',
		'public'        	 => true,
		'menu_position' 	 => 5,
		'publicly_queryable' => true,
		'show_ui' 			 => true,   
    	'show_in_menu'		 => true,
    	'capability_type' 	 => 'post',
    	'hierarchical'		 => false,
    	'query_var' 		 => true,   
    	'rewrite' 			 => true,  
		'supports'      	 => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
		'has_archive'   	 => true
	);
	register_post_type( 'project', $args );	


	// Initializing Taxonomy Labels  
	$labels = array(  
	    'name' => _x( 'Tags', 'taxonomy general name' ),  
	    'singular_name' => _x( 'Tag', 'taxonomy singular name' ),  
	    'search_items' =>  __( 'Search Types' ),  
	    'all_items' => __( 'All Tags' ),  
	    'parent_item' => __( 'Parent Tag' ),  
	    'parent_item_colon' => __( 'Parent Tag:' ),  
	    'edit_item' => __( 'Edit Tags' ),  
	    'update_item' => __( 'Update Tag' ),  
	    'add_new_item' => __( 'Add New Tag' ),  
	    'new_item_name' => __( 'New Tag Name' ),  
	);  
      
	// Registering A Custom Taxonomy  
	register_taxonomy('tagportfolio',array('project'), array(  
	    'hierarchical' => true, // define whether to use a system like tags or categories  
	    'labels' => $labels,  
	    'show_ui' => true,  
	    'query_var' => true,  
	    'rewrite' => array( 'slug' => 'tag-portfolio' ),  
	));  

}  /* END jcn_project_custom_init --*/  

/* Creating a custom message for jcn_project_updated_messages */  
add_filter('post_updated_messages', 'jcn_project_updated_messages');  
  
function jcn_project_updated_messages( $messages ) {  
  global $post, $post_ID;  
  /* I recieved this code from http://wp.tutsplus.com/ */
  $messages['project'] = array(  
    0 => '', // Unused. Messages start at index 1.  
    1 => sprintf( __('Project updated. <a href="%s">View project</a>'), esc_url( get_permalink($post_ID) ) ),  
    2 => __('Custom field updated.'),  
    3 => __('Custom field deleted.'),  
    4 => __('Project updated.'),  
    /* translators: %s: date and time of the revision */  
    5 => isset($_GET['revision']) ? sprintf( __('Project restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,  
    6 => sprintf( __('Project published. <a href="%s">View project</a>'), esc_url( get_permalink($post_ID) ) ),  
    7 => __('Project saved.'),  
    8 => sprintf( __('Project submitted. <a target="_blank" href="%s">Preview project</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),  
    9 => sprintf( __('Project scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview project</a>'),  
    // translators: Publish box date format, see http://php.net/date  
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),  
    10 => sprintf( __('Project draft updated. <a target="_blank" href="%s">Preview project</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),  
  );  
  
  return $messages;  
}    
/* END jcn_project_updated_messages */  

/*--- Adding a custom URL meta box for our Portfolio-Filter Plugin ---*/

/*--- Recieved Code from http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields/
	  and http://codex.wordpress.org/Function_Reference/add_meta_box ---*/
/*--- Also recieved help from http://wp.tutsplus.com/ and class notes ---*/
add_action('admin_init','portfolio_meta_init');  
  
function portfolio_meta_init()  
{  
    // add a meta box for WordPress 'project' type  
    add_meta_box('portfolio_meta', 'Project Information', 'portfolio_meta_setup', 'project', 'side', 'low');  
   
    // add a callback function to save any data a user enters in  
    add_action('save_post','portfolio_meta_save');  
}  
  
function portfolio_meta_setup()  
{  
    global $post;  
       
    ?>  
        <div class="portfolio_meta_control">  
            <label>URL</label>  
            <p>  
                <input type="text" name="_url" value="<?php echo get_post_meta($post->ID,'_url',TRUE); ?>" style="width: 100%;" />  
            </p>  
        </div>  
    <?php  
  
    // create for validation  
    echo '<input type="hidden" name="meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';  
}  
  
function portfolio_meta_save($post_id)   
{  
    // check nonce  
    if (!isset($_POST['meta_noncename']) || !wp_verify_nonce($_POST['meta_noncename'], __FILE__)) {  
    return $post_id;  
    }  
  
    // check capabilities  
    if ('post' == $_POST['post_type']) {  
    if (!current_user_can('edit_post', $post_id)) {  
    return $post_id;  
    }  
    } elseif (!current_user_can('edit_page', $post_id)) {  
    return $post_id;  
    }  
  
    // exit on autosave  
    if (defined('DOING_AUTOSAVE') == DOING_AUTOSAVE) {  
    return $post_id;  
    }  
  
    if(isset($_POST['_url']))   
    {  
        update_post_meta($post_id, '_url', $_POST['_url']);  
    } else   
    {  
        delete_post_meta($post_id, '_url');  
    }  
}  
  
/*--- END custom URL meta box for our Portfolio-Filter Plugin ---*/  
/*--- Begin including template page for Portfolio-Filter---*/
/*-- I recieved help from Brian Ferdinand with the Template Fallback --*/
//Template fallback
add_action("template_redirect", 'my_theme_redirect');

function my_theme_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );


    //A Custom Taxonomy Page
    if ($wp->query_vars["taxonomy"] == 'tagportfolio') {
        $templatefilename = 'page_portfolio_2c.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect($return_template);

    //A Simple Page
    } elseif ($wp->query_vars["pagename"] == 'page-portfolio-3-column') {
        $templatefilename = 'page_portfolio_2c.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect($return_template);
    }
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}
/*--- END including template page for Portfolio-Filter---*/
/*--- Begin Enqueque Stylesheet ---*/
function jcn_project_enqueue_style() {
    wp_enqueue_style(
        'stylesheet', plugins_url( '/css/style.css' , __FILE__ ), array(),'1.54','all'
    );
} 
add_action( 'wp_enqueue_scripts', 'jcn_project_enqueue_style' );
/*--- END Enqueque Stylesheet ---*/
/*--- Begin Enqueque Scripts ---*/
function jcn_project_enqueue_filterable() 
    {
        wp_register_script( 'filterable', plugins_url( '/js/filterable.js' , __FILE__ ), array( 'jquery' ) );
        wp_enqueue_script( 'filterable' );
}
add_action('wp_enqueue_scripts', 'jcn_project_enqueue_filterable');
/*--- END Enqueque Scripts ---*/
