<?php

/**
 * Hatena Authentication class
 *
 * @package app.Vendor.Hybrid.Providers
 * @author Mai Nhut Tan
 * @since 2013/09/10
 */
require_once Hybrid_Auth::$config["path_libraries"] . "OAuth/OAuth.php";
require_once Hybrid_Auth::$config["path_libraries"] . "OAuth/OAuth1Client.php";

class Hybrid_Providers_Hatena extends Hybrid_Provider_Model_OAuth1
{
    /**
     * adapter initializer
     *
     * @param void
     * @return void
     * @author Mai Nhut Tan
     * @since 2013/09/10
     */
    function initialize()
    {
        // 1 - check application credentials
        if ( ! $this->config["keys"]["key"] || ! $this->config["keys"]["secret"] ){
            throw new Exception( "Your application key and secret are required in order to connect to {$this->providerId}.", 4 );
        }

        // 3.1 - setup access_token if any stored
        if( $this->token( "access_token" ) ){
            $this->api = new HatenaOAuth(
                $this->config["keys"]["key"], $this->config["keys"]["secret"],
                $this->token( "access_token" ), $this->token( "access_token_secret" )
            );
        }

        // 3.2 - setup request_token if any stored, in order to exchange with an access token
        elseif( $this->token( "request_token" ) ){
            $this->api = new HatenaOAuth(
                $this->config["keys"]["key"], $this->config["keys"]["secret"],
                $this->token( "request_token" ), $this->token( "request_token_secret" )
            );
        }

        // 3.3 - instanciate OAuth client with client credentials
        else{
            $this->api = new HatenaOAuth( $this->config["keys"]["key"], $this->config["keys"]["secret"] );
        }

        // Set curl proxy if exist
        if( isset( Hybrid_Auth::$config["proxy"] ) ){
            $this->api->curl_proxy = Hybrid_Auth::$config["proxy"];
        }

        $this->api->api_base_url      = "http://n.hatena.com/";
        $this->api->authorize_url     = "https://www.hatena.ne.jp/oauth/authorize";
        $this->api->request_token_url = "https://www.hatena.com/oauth/initiate";
        $this->api->access_token_url  = "https://www.hatena.com/oauth/token";
    }

    /**
     * Get user profile
     *
     * @param void
     * @return void
     * @author Mai Nhut Tan
     * @since 2013/09/24
     */
    function getUserProfile()
    {
        $response = $this->api->get( 'applications/my.json' );

        // check the last HTTP status code returned
        if ( $this->api->http_code != 200 ){
            throw new Exception( "User profile request failed! {$this->providerId} returned an error. " . $this->errorMessageByStatus( $this->api->http_code ), 6 );
        }

        if ( ! is_object( $response ) || ! isset( $response->url_name ) ){
            throw new Exception( "User profile request failed! {$this->providerId} api returned an invalid response.", 6 );
        }

        $this->user->profile->identifier    = property_exists($response, 'url_name') ? $response->url_name : '';
        $this->user->profile->displayName   = property_exists($response, 'display_name') ? $response->display_name : '';
        $this->user->profile->photoURL      = property_exists($response, 'profile_image_url') ? $response->profile_image_url : '';

        return $this->user->profile;
    }

}

/**
 * HatenaOAuth class
 *
 * @package app.Vendor.Hybrid.Providers
 * @author Mai Nhut Tan
 * @since 2013/09/24
 */
class HatenaOAuth extends OAuth1Client{

    function requestToken( $callback = null )
    {
        $parameters = array(
            'scope' => 'read_public'
        );

        if ( $callback ) {
            $this->redirect_uri = $parameters['oauth_callback'] = $callback;
        }

        $request     = $this->signedRequest( $this->request_token_url, $this->request_token_method, $parameters );
        $token       = OAuthUtil::parse_parameters( $request );
        $this->token = new OAuthConsumer( $token['oauth_token'], $token['oauth_token_secret'] );

        return $token;
    }
}
