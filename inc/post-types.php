<?php 
	
	function registrar_post_types() {
	    $args = array(
	       	'label'              => 'Videos',
	    	'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'taxonomies'         => array('category'),
			'rewrite'            => array( 'slug' => 'videos' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 3,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	    );
	    register_post_type( 'videos', $args );

	}
	add_action( 'init', 'registrar_post_types' );