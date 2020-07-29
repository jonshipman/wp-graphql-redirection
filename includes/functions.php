<?php

// Shared functions.

function gql_redirection_to_global_id( $id ) {
	return \GraphQLRelay\Relay::toGlobalId( 'redirection', $id );
}

function gql_redirection_from_global_id( $id ) {
	return \GraphQLRelay\Relay::fromGlobalId( $id );
}
