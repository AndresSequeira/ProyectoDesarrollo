<?php
/**
 * Theme setup for Bajoterra Minimal Child.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bmc_enqueue_styles() {
	wp_enqueue_style(
		'bmc-minimal',
		get_stylesheet_directory_uri() . '/assets/css/minimal.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'bmc_enqueue_styles' );

function bmc_theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'bmc_theme_setup' );
