<?php

// Adds the GraphQL registration.

add_filter(
	'graphql_data_loaders',
	function( $loaders, $context ) {
		$loaders['redirection'] = new WPGraphQL\Data\Loader\RedirectionItemLoader( $context );
		return $loaders;
	},
	10,
	2
);

add_action(
	'graphql_register_types',
	function () {
		register_graphql_field(
			'RootQuery',
			'Redirection',
			array(
				'type'        => 'RedirectionItem',
				'description' => __( 'Redirection item', 'gql-redirection' ),
				'args'        => array(
					'id' => array(
						'type'        => 'ID',
						'description' => __( 'The globally unique identifier of the redirection object', 'gql-redirection' ),
					),
				),
				'resolve'     => function ( $source, $args, $context, $info ) {
					$id_parts = gql_redirection_from_global_id( $source['id'] );

					if ( empty( $id_parts ) ) {
						return null;
					}

					list( 'id' => $id, 'type' => $type ) = $id_parts;
					if ( 'redirection' !== $type ) {
						return null;
					}

					return $context->get_loader( 'redirection' )->load_deferred( $id );
				},
			)
		);

		register_graphql_connection(
			array(
				'fromType'       => 'RootQuery',
				'toType'         => 'RedirectionItem',
				'fromFieldName'  => 'Redirections',
				'connectionArgs' => array(
					'matchUrl'   => array(
						'type'        => 'String',
						'description' => __( 'The url to match, e.g. Source Url', 'gql-redirection' ),
					),
					'orderby'   => array(
						'type'        => 'String',
						'description' => __( 'Field to order the query on', 'gql-redirection' ),
					),
					'url'        => array(
						'type'        => 'String',
						'description' => __( 'The destination url, e.g. Target Url', 'gql-redirection' ),
					),
					'actionCode' => array(
						'type'        => 'Integer',
						'description' => __( 'The action code to the action type', 'gql-redirection' ),
					),
					'status'     => array(
						'type'        => 'RedirectionItemStatusEnum',
						'description' => __( 'The status of the item', 'gql-redirection' ),
					),
				),
				'resolve'        => function ( $source, $args, $context, $info ) {
					$resolver = new WPGraphQL\Data\Connection\RedirectionItemConnectionResolver( $source, $args, $context, $info );
					return $resolver->get_connection();
				},
			)
		);
	}
);
