<?php

namespace WPGraphQL\Data\Connection;

use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;
use WPGraphQL\Types;

/**
 * Class RedirectionItemConnectionResolver
 */
class RedirectionItemConnectionResolver extends AbstractConnectionResolver {

	public function __construct( $source, $args, $context, $info ) {
		/**
		 * Call the parent construct to setup class data
		 */
		parent::__construct( $source, $args, $context, $info );
	}

	/**
	 * Return the name of the loader
	 *
	 * @return string
	 */
	public function get_loader_name() {
		return 'redirection';
	}

	public function get_query() {
		global $wpdb;

		$query_args = $this->get_query_args();

		$last  = ! empty( $this->args['last'] ) ? $this->args['last'] : null;
		$first = ! empty( $this->args['first'] ) ? $this->args['first'] : null;

		$query_args = $this->query_args ?: array();

		$orderby = 'id';
		if ( ! empty( $query_args['orderby'] ) ) {
			$orderby = $query_args['orderby'];
			unset( $query_args['orderby'] );
		}

		$cursor_offset = $this->get_offset();

		$sql   = "SELECT id FROM {$wpdb->prefix}redirection_items";
		$where = array();

		if ( ! empty( $query_args ) ) {
			foreach ( $query_args as $key => $value ) {
				switch ( $key ) {
					case 'match_url':
					case 'url':
						$where[] = $wpdb->prepare( "(`{$key}` LIKE %s OR `{$key}` LIKE %s OR `{$key}` LIKE %s OR `{$key}` LIKE %s)", $value, "/$value/", "/$value", "$value/" );

						break;
					default:
						$where[] = $wpdb->prepare( "`{$key}` LIKE %s", $value );
				}
			}
		}

		if ( $cursor_offset ) {
			if ( ! empty( $last ) ) {
				$where[] = $wpdb->prepare( '`id` < %d', $cursor_offset );
			} else {
				$where[] = $wpdb->prepare( '`id` > %d', $cursor_offset );
			}
		}

		if ( ! empty( $where ) ) {
			$sql .= ' WHERE ' . implode( ' AND ', $where );
		}

		$order = ! empty( $last ) ? 'DESC' : 'ASC';

		$sql .= " ORDER BY `{$orderby}` {$order}";

		if ( ! empty( $first ) ) {
			$sql .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $first + 1, 0 );
		} elseif ( ! empty( $last ) ) {
			$sql .= $wpdb->prepare( ' LIMIT %d OFFSET %d', $last + 1, 0 );
		}

		$ids = $wpdb->get_col( $sql . ';' );

		if ( ! empty( $last ) ) {
			$ids = array_reverse( $ids );
		}

		return $ids;
	}

	public function get_ids() {
		$ids     = array();
		$queried = $this->get_query();

		if ( empty( $queried ) ) {
			return $ids;
		}

		return $queried;
	}

	public function get_node_by_id( $id ) {
		return $this->loader->load( $id );
	}

	public function should_execute() {
		return true;
	}

	public function get_query_args() {
		$query_args = $this->query_args ?: array();

		$input_fields = array();
		if ( ! empty( $this->args['where'] ) ) {
			$input_fields = $this->sanitize_input_fields( $this->args['where'] );
		}

		if ( ! empty( $input_fields ) ) {
			$query_args = array_merge( $query_args, $input_fields );
		}

		return $query_args;
	}

	public function sanitize_input_fields( $where_args ) {
		$arg_mapping = array(
			'actionCode'      => 'action_code',
			'actionData'      => 'action_data',
			'actionType'      => 'action_type',
			'databaseId'      => 'id',
			'groupDatabaseId' => 'group_id',
			'lastAccess'      => 'last_access',
			'lastCount'       => 'last_count',
			'matchData'       => 'match_data',
			'matchType'       => 'match_type',
			'matchUrl'        => 'match_url',
		);

		$query_args = Types::map_input( $where_args, $arg_mapping );

		if ( isset( $query_args['match_url'] ) ) {
			$query_args['match_url'] = trim( $query_args['match_url'], " \t\n\r\0\x0B/" );
		}

		if ( isset( $query_args['url'] ) ) {
			$query_args['url'] = trim( $query_args['url'], " \t\n\r\0\x0B/" );
		}

		if ( isset( $query_args['orderby'] ) ) {
			$orderby               = Types::map_input( array( $query_args['orderby'] ), $arg_mapping );
			$query_args['orderby'] = $orderby[0];
		}

		return ! empty( $query_args ) && is_array( $query_args ) ? $query_args : array();
	}

	public function is_valid_offset( $offset ) {
		return true;
	}
}
