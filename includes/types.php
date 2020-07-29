<?php

// GraphQL Types.

add_action(
	'graphql_register_types',
	function() {
		register_graphql_enum_type(
			'RedirectionItemStatusEnum',
			array(
				'description' => __( 'Statuses', 'gql-redirection' ),
				'values'      => array(
					'ENABLED'  => 'enabled',
					'DISABLED' => 'disabled',
				),
			)
		);

		register_graphql_object_type(
			'RedirectionItemMatchDataSource',
			array(
				'description' => __( 'Nonces for the forms', 'gql-redirection' ),
				'fields'      => array(
					'flagRegex' => array(
						'type'        => 'Boolean',
						'description' => __( 'Match data source flag regex', 'gql-redirection' ),
					),
				),
			)
		);

		register_graphql_object_type(
			'RedirectionItemMatchData',
			array(
				'description' => __( 'Nonces for the forms', 'gql-redirection' ),
				'fields'      => array(
					'source' => array(
						'type'        => 'RedirectionItemMatchDataSource',
						'description' => __( 'Match data source', 'gql-redirection' ),
					),
				),
			)
		);

		register_graphql_object_type(
			'RedirectionItem',
			array(
				'description' => __( 'Nonces for the forms', 'gql-redirection' ),
				'fields'      => array(
					'id'              => array(
						'type'        => 'ID',
						'description' => __( 'The globally unique identifier of the redirection object', 'gql-redirection' ),
					),
					'databaseId'      => array(
						'type'        => 'ID',
						'description' => __( 'The WordPress object id', 'gql-redirection' ),
					),
					'url'             => array(
						'type'        => 'String',
						'description' => __( 'The target url', 'gql-redirection' ),
					),
					'matchUrl'        => array(
						'type'        => 'String',
						'description' => __( 'The match url', 'gql-redirection' ),
					),
					'matchData'       => array(
						'type'        => 'RedirectionItemMatchData',
						'description' => __( 'The match data', 'gql-redirection' ),
					),
					'regex'           => array(
						'type'        => 'Boolean',
						'description' => __( 'Whether the item uses regex', 'gql-redirection' ),
					),
					'position'        => array(
						'type'        => 'Integer',
						'description' => __( 'The position of the item', 'gql-redirection' ),
					),
					'lastCount'       => array(
						'type'        => 'Integer',
						'description' => __( 'The number of times the redirect was used', 'gql-redirection' ),
					),
					'lastAccess'      => array(
						'type'        => 'String',
						'description' => __( 'The last item the item was accessed', 'gql-redirection' ),
					),
					'groupDatabaseId' => array(
						'type'        => 'ID',
						'description' => __( 'The associated group database id', 'gql-redirection' ),
					),
					'status'          => array(
						'type'        => 'RedirectionItemStatusEnum',
						'description' => __( 'The status of the item', 'gql-redirection' ),
					),
					'actionType'      => array(
						'type'        => 'String',
						'description' => __( 'The action type of the item', 'gql-redirection' ),
					),
					'actionCode'      => array(
						'type'        => 'Integer',
						'description' => __( 'The action code to the action type', 'gql-redirection' ),
					),
					'actionData'      => array(
						'type'        => 'String',
						'description' => __( 'The action data of the item', 'gql-redirection' ),
					),
					'matchType'       => array(
						'type'        => 'String',
						'description' => __( 'The match type of the item', 'gql-redirection' ),
					),
					'title'           => array(
						'type'        => 'String',
						'description' => __( 'The title of the item', 'gql-redirection' ),
					),
				),
			)
		);
	}
);
