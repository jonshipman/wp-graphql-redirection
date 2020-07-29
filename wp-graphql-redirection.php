<?php

/*
Plugin Name: WPGraphql Redirection
Description: A WPGraphQL Extension that adds support for John Godley's Redirection.
Version: 1.0
Author: Jon Shipman
Text Domain: gql-redirection

============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages(including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort(including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.

============================================================================================================
*/

require_once 'includes/functions.php';
require_once 'includes/types.php';
require_once 'includes/resolvers.php';

add_action(
	'plugins_loaded',
	function() {

		// Checks to see if Redirection is installed.
		// Then deactivate.
		if ( ! defined( 'REDIRECTION_FILE' ) ) {
			deactivate_plugins( __FILE__ );
		} else {

			// Require the WPGraphQL classes.
			if ( function_exists( 'register_graphql_field' ) ) {
				require_once 'includes/class-redirection-item-model.php';
				require_once 'includes/class-redirection-item-connection-resolver.php';
				require_once 'includes/class-redirection-item-loader.php';
			}
		}
	}
);
