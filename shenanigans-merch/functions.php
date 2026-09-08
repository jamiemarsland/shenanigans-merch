<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_action( 'after_setup_theme', function () {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/shop.css' );
} );
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'shenanigans-shop', get_theme_file_uri( 'assets/shop.css' ), array(), '1.0.0' );
    if ( function_exists( 'is_product' ) && is_product() ) {
        wp_enqueue_script( 'shenanigans-sizes', get_theme_file_uri( 'assets/sizes.js' ), array( 'jquery', 'wc-add-to-cart-variation' ), '1.0.0', true );
    }
} );
