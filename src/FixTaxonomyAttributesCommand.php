<?php

namespace cr0ybot\TaxonomyToWCAttribute;

use WP_CLI;
use WP_CLI_Command;

class FixTaxonomyAttributesCommand extends WP_CLI_Command {

	/**
	 * Fix taxonomy attributes.
	 *
	 * Bulk edit products to add an attribute for *existing* taxonomy term
	 * relationships. This means you must have already transferred taxonomy
	 * terms to a WooCommerce attribute taxonomy. This script will make the
	 * attribute terms visible on the product.
	 *
	 * ## OPTIONS
	 *
	 * <attribute>
	 * : Attribute name (without pa_ prefix).
	 *
	 * @param array $args       Indexed array of positional arguments.
	 * @param array $assoc_args Associative array of associative arguments.
	 */
	public function __invoke( $args, $assoc_args ) {
		if ( ! is_woocommerce_activated() ) {
			WP_CLI::error( 'WooCommerce is not activated.' );
		}

		$attribute_name = wc_attribute_taxonomy_name( $args[0] );

		// If the attribute taxonomy doesn't exist, exit.
		if ( ! taxonomy_exists( $attribute_name ) ) {
			WP_CLI::error( 'Attribute taxonomy does not exist:' . $attribute_name );
		}

		add_filter(
			'woocommerce_product_data_store_cpt_get_products_query',
			array(
				$this,
				'filter_tax_query',
			),
			10,
			2
		);

		// Batch process attribute taxonomy terms.
		$per_batch   = 100;
		$term_offset = 0;
		$terms       = get_terms(
			array(
				'taxonomy'   => $attribute_name,
				'hide_empty' => true,
				'number'     => $per_batch,
				'offset'     => $term_offset,
			)
		);

		while ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				// Batch process products.
				$product_page = 1;
				$products     = wc_get_products(
					array(
						'limit'     => $per_batch,
						'page'      => $product_page,
						'tax_query' => array(
							array(
								'taxonomy' => $attribute_name,
								'field'    => 'slug',
								'terms'    => $term->slug,
							),
						),
					)
				);

				while ( ! empty( $products ) ) {
					foreach ( $products as $product ) {
						$product_id = $product->get_id();

						$product_terms = array( $term->term_id );

						// Get current attributes array.
						$existing_attributes = $product->get_attributes( 'edit' );

						// Check if the product already has the attribute.
						$existing_attribute = $existing_attributes[ $attribute_name ] ?? null;
						if ( $existing_attribute ) {
							// Check if the attribute term is already set.
							$existing_terms = $existing_attribute->get_terms();
							foreach ( $existing_terms as $existing_term ) {
								if ( $existing_term->term_id === $term->term_id ) {
									continue 2; // Skip to the next product.
								}
							}

							// Add the term to the existing attribute.
							$product_terms = array_merge( wp_list_pluck( $existing_terms, 'ID' ), $product_terms );
						}

						// Get the attribute taxonomy ID.
						$id = wc_attribute_taxonomy_id_by_name( $attribute_name );
						if ( ! $id ) {
							continue;
						}

						if ( $existing_attribute ) {
							WP_CLI::line( 'Adding term "' . $term->slug . '" to existing attribute "' . $attribute_name . '" for product: ' . $product_id . ' - ' . $product->get_name() );
							// Update existing attribute options.
							$existing_attribute->set_options( $product_terms );
							$attribute = $existing_attribute;
						} else {
							WP_CLI::line( 'Adding attribute "' . $attribute_name . '" with term "' . $term->slug . '" to product: ' . $product_id . ' - ' . $product->get_name() );
							// Create new attribute.
							$attribute = new \WC_Product_Attribute();
							$attribute->set_id( $id );
							$attribute->set_name( $attribute_name );
							$attribute->set_options( $product_terms );
							$attribute->set_position( count( $existing_attributes ) );
							$attribute->set_visible( 1 );
						}

						// Add attribute to the existing attributes array.
						$existing_attributes[ $attribute_name ] = $attribute;

						$product->set_attributes( $existing_attributes );
						$product->save();
					}

					// Query next page.
					++$product_page;
					$products = wc_get_products(
						array(
							'limit'     => $per_batch,
							'page'      => $product_page,
							'tax_query' => array(
								array(
									'taxonomy' => $attribute_name,
									'field'    => 'slug',
									'terms'    => $term->slug,
								),
							),
						)
					);
				}
			}

			// Query next batch of terms.
			$term_offset += $per_batch;
			$terms        = get_terms(
				array(
					'taxonomy'   => $attribute_name,
					'hide_empty' => true,
					'number'     => $per_batch,
					'offset'     => $term_offset,
				)
			);
		}

		WP_CLI::success( 'Done.' );
	}

	/**
	 * Filter the products query to include tax_query.
	 *
	 * @param array $query Query.
	 * @param array $query_args Query arguments.
	 *
	 * @return array
	 */
	public function filter_tax_query( $query, $query_args ) {
		if ( isset( $query_args['tax_query'] ) ) {
			// Pass tax_query to the query.
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			$query['tax_query'] = $query_args['tax_query'];
		}

		return $query;
	}
}
