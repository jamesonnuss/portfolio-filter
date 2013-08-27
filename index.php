<?php
/*
Plugin URI: http://www.jamesonnuss.com
Version: 1.0
Description: Portfolio Filter is a versatile plugin designed to efficiently create a Portfolio Gallery. Portfolio Filter breaks apart your work into different categories, making it easier to navigate. Impress potential clients with a clean portfolio site that truly shows off your work in an organized fashion. Portfolio Filter can also be used to create a simple image gallery that contains multiple image categories. For those who are in need of a fast, light and useful plugin that makes your portfolio come to life. Try out the free plugin today and see how easy it is to create a functional, dynamic portfolio. 
Plugin Name: Porfolio Filter
Author: Jameson Nuss
Author URI: http://www.jamesonnuss.com
License: GPLV2
*/


add_action('init', 'jcn_project_custom_init');      
      
/* project_custom_init */  
function jcn_project_custom_init()  
{  
   $labels = array(
		'name'               => _x( 'Portfolio Filter', 'post type general name' ),
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

}  /* End jcn_project_custom_init --*/  

