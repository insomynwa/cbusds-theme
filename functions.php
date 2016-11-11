<?php

// Load CBUS w3Css scripts (header.php)
function cbusdscss_header_scripts() {
	if( $GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
		wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
        wp_enqueue_script('conditionizr'); // Enqueue it!

        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr'); // Enqueue it!

		wp_register_script('jquery-marquee', get_template_directory_uri() . '/js/jquery.marquee.min.js', array('jquery') ,''); // Conditionizr
        wp_enqueue_script('jquery-marquee'); // Enqueue it!

        wp_register_script('cbusdsscripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('cbusdsscripts'); // Enqueue it!

        wp_localize_script( 'cbusdsscripts', 'cbusds_ajax', array('ajaxurl' => admin_url( 'admin-ajax.php'), 'security' => wp_create_nonce('cbusds-special-string')));

	}
}

// Load CBUS w3css styles
function cbusds_styles()
{

    wp_register_style('w3css', get_template_directory_uri() . '/css/w3.css', array(), '', 'all');
    wp_enqueue_style('w3css'); // Enqueue it!

    wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize'); // Enqueue it!

    wp_register_style('cbusdscss', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('cbusdscss'); // Enqueue it!
}

// Add Actions
add_action('init', 'cbusdscss_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_enqueue_scripts', 'cbusds_styles'); // Add Theme Stylesheet

function cbusds_widgets_init() {

    register_sidebar( array(
        'name'          => 'Widget 1',
        'id'            => 'cbus_widget_1',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        // 'before_title'  => '<h2 class="">',
        // 'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
        'name'          => 'Widget 2',
        'id'            => 'cbus_widget_2',
        'before_widget' => '<div class="w3-container">',
        'after_widget'  => '</div>',
        // 'before_widget' => '<h2 class="">',
        // 'after_title'   => '</h2>'
        )
    );

    register_sidebar( array(
        'name'          => 'Widget 3',
        'id'            => 'cbus_widget_3',
        'before_widget' => '<div id="apixu-widget" class="w3-container">',
        'after_widget'  => '</div>',
        // 'before_widget' => '<h2 class="">',
        // 'after_title'   => '</h2>'
        )
    );

    register_sidebar( array(
        'name'          => 'Widget 4',
        'id'            => 'cbus_widget_4',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        // 'before_widget' => '<h2 class="">',
        // 'after_title'   => '</h2>'
        )
    );

}
add_action( 'widgets_init', 'cbusds_widgets_init' );

?>