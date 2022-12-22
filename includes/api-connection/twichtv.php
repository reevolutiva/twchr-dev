<?php
/*
 * TwitchTV API code by Elias Ranz-Schleifer
 * Thank you for using my code please refer to
 * https://github.com/Xxplosions/twitchtv-oauth for future updates
 * Have questions or feedback? Contact Elias on Twitter (https://twitter.com/xxplosions)
 * Check out my livestream at http://twitch.tv/xxplosions (It would be amazing to chat with you about future updates)
	/: WordPress comes with an extensive HTTP API that should be used instead of creating your own curl calls. It’s both faster and more extensive. It’ll fall back to curl if it has to, but it’ll use a lot of WordPress’ native functionality first.
 */

class TwitchTV {
	var $base_url = 'https://api.twitch.tv/kraken/';
	var $client_id = '';
	var $client_secret = '';
	var $redirect_url = '';
	var $scope_array = array( 'user_read', 'channel_read', 'chat_login', 'user_follows_edit', 'channel_editor', 'channel_commercial', 'channel_check_subscription' );

	  /**
	   * Channel data for the fetched user
	   *
	   * @var stdClass
	   */
	  var $channel_data = null;
	  var $curl_cache;

	public function __construct( $client_id, $client_secret, $redirect_url, $scope_array ) {
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_url = $redirect_url;
		$this->scope_array = $scope_array;
	}

	/**
	 * Generates a link based on the desired scope
	 *
	 * @return string         URL that is used to gain permissions for TwitchTV Authentication
	 */
	public function authenticate() {
		$i      = 0;
		$return = '';
		$len    = count( $this->scope_array );
		// search through the scope array and append a + foreach all but the last element
		foreach ( $this->scope_array as $scope ) {
			if ( $i == $len - 1 ) {
				$scope .= '';
				$return .= $scope;
			} else {
				$scope .= '+';
				$return .= $scope;
			}

			$i++;
		}
		// initiate connection to the twitch.tv servers
		$scope            = $return;
		$authenticate_url = $this->base_url . 'oauth2/authorize?response_type=code&client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_url . '&scope=' . $scope;
		return $authenticate_url;
	}
}
