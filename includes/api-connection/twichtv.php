<?php
class TwitchTV {
    var $base_url = "https://api.twitch.tv/kraken/";
    var $client_id = '';
    var $client_secret = '';
    var $redirect_url = '';
    var $scope_array = array('user_read','channel_read','chat_login','user_follows_edit','channel_editor','channel_commercial','channel_check_subscription');
 
    /**
     * Generates a link based on the desired scope
     *
     * @return string         URL that is used to gain permissions for TwitchTV Authentication
     */
    public function authenticate() {
        $i      = 0;
        $return = '';
        $len    = count($this->scope_array);
        //search through the scope array and append a + foreach all but the last element
        foreach ($this->scope_array as $scope) {
            if ($i == $len - 1) {
                $scope .= "";
                $return .= $scope;
            } else {
                $scope .= "+";
                $return .= $scope;
            }

            $i++;
        }
        //initiate connection to the twitch.tv servers
        $scope            = $return;
        $authenticate_url = $this->base_url . 'oauth2/authorize?response_type=code&client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_url . '&scope=' . $scope;
        return $authenticate_url;
    }
}
