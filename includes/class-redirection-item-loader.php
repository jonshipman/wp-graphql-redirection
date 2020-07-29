<?php

namespace WPGraphQL\Data\Loader;

use WPGraphQL\Model\RedirectionItem;

/**
 * Class RedirectionItemLoader
 *
 * @package WPGraphQL\Data\Loader
 */
class RedirectionItemLoader extends AbstractDataLoader {
	public function loadKeys( array $keys ) {
		global $wpdb;

		if ( empty( $keys ) ) {
			return $keys;
		}

		$loaded = array();

		foreach ( $keys as $key ) {
			if ( empty( $key ) ) {
				$loaded[ $key ] = null;
				continue;
			}

			$sql                     = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}redirection_items WHERE id = %d", $key );
			$redirection_item_object = $wpdb->get_row( $sql );

			$loaded[ $key ] = new RedirectionItem( $redirection_item_object );
		}

		return ! empty( $loaded ) ? $loaded : array();
	}
}
