<?php

namespace WPGraphQL\Model;

use GraphQLRelay\Relay;

/**
 * Class RedirectionItem - Models data for redirection items.
 */
class RedirectionItem extends Model {

	/**
	 * Stores the incoming WP_RedirectionItem to be modeled
	 *
	 * @var \WP_RedirectionItem $data
	 */
	protected $data;

	/**
	 * RedirectionItem constructor.
	 *
	 * @param \WP_RedirectionItem $wpdb_row The incoming WP_RedirectionItem to be modeled
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function __construct( object $wpdb_row ) {
		$this->data = $wpdb_row;
		parent::__construct();
	}

	/**
	 * Method for determining if the data should be considered private or not
	 *
	 * @return bool
	 */
	protected function is_private() {
		return false;
	}

	/**
	 * Initialize the object
	 *
	 * @return void
	 */
	protected function init() {
		$ID = ! empty( $this->data->id ) ? absint( $this->data->id ) : null;

		if ( empty( $this->fields ) ) {
			$this->fields = array(
				'id'              => function() use ( $ID ) {
					return gql_redirection_to_global_id( $ID );
				},
				'databaseId'      => function() use ( $ID ) {
					return $ID;
				},
				'url'             => function() {
					return ! empty( $this->data->url ) ? $this->data->url : null;
				},
				'matchUrl'        => function() {
					return ! empty( $this->data->match_url ) ? $this->data->match_url : null;
				},
				'matchData'       => function() {
					$matchData = ! empty( $this->data->match_data ) ? json_decode( $this->data->match_data ) : null;
					if ( empty( $matchData ) ) {
						return null;
					}

					$source = isset( $matchData->source ) ? $matchData->source : null;

					if ( empty( $source ) ) {
						return array( 'source' => null );
					}

					$flagRegex = isset( $source->flag_regex ) ? $source->flag_regex : null;

					return array(
						'source' => array(
							'flagRegex' => $flagRegex,
						),
					);
				},
				'regex'           => function() {
					return 1 === $this->data->regex;
				},
				'position'        => function() {
					return ! empty( $this->data->position ) ? $this->data->position : null;
				},
				'lastCount'       => function() {
					return ! empty( $this->data->last_count ) ? $this->data->last_count : null;
				},
				'lastAccess'      => function() {
					return ! empty( $this->data->last_access ) ? $this->data->last_access : null;
				},
				'groupDatabaseId' => function() {
					return ! empty( $this->data->group_id ) ? absint( $this->data->group_id ) : null;
				},
				'status'          => function() {
					return ! empty( $this->data->status ) ? $this->data->status : null;
				},
				'actionType'      => function() {
					return ! empty( $this->data->action_type ) ? $this->data->action_type : null;
				},
				'actionCode'      => function() {
					return ! empty( $this->data->action_code ) ? absint( $this->data->action_code ) : null;
				},
				'actionData'      => function() {
					return ! empty( $this->data->action_data ) ? $this->data->action_data : null;
				},
				'matchType'       => function() {
					return ! empty( $this->data->match_type ) ? $this->data->match_type : null;
				},
				'title'           => function() {
					return ! empty( $this->data->title ) ? $this->data->title : null;
				},
			);
		}
	}
}
