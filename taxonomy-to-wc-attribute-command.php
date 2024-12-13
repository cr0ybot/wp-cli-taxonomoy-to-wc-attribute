<?php

namespace cr0ybot\TaxonomyToWCAttribute;

use WP_CLI;

if ( ! class_exists( '\WP_CLI' ) ) {
	return;
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	/**
	 * Check if WooCommerce plugin is activated.
	 */
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) {
			return true;
		} else {
			return false; }
	}
}

$taxonomy_to_wc_attribute_autoloader = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $taxonomy_to_wc_attribute_autoloader ) ) {
	require_once $taxonomy_to_wc_attribute_autoloader;
}

WP_CLI::add_command( 'fix-taxonomy-attributes', FixTaxonomyAttributesCommand::class );
