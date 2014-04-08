php-oauth-v1-light-client
=========================

PHP OAuth v1.0 light client

## request_token request

    $REQUEST_TOKEN = 'https://api.domain.com/oauth/request_token';
    include 'oauth.inc';
    $oauth = array (
      'consumer_key'    => '****',
      'consumer_secret' => '****',
    );
    $oauth['oauth_callback'] = 'http://www.clientdomain.com/callback/test';
    request_token($oauth);

    print 'Save request_token: oauth_token=' . $oauth['oauth_token'] . " oauth_token_secret=" . $oauth['oauth_token_secret'] . "\n";
    print 'Redirect user to: https://api.domain.com/oauth/authorize?oauth_token=' . $oauth['oauth_token'];

## access_token request

After user redirects, verifier will be received in callback e.g., http://www.clientdomain.com/callback/test?oauth_token=****&oauth_verifier=****

    $ACCESS_TOKEN  = 'https://api.domain.com/oauth/access_token';
    include 'oauth.inc';
    $oauth = array (
      'consumer_key'       => '****',
      'consumer_secret'    => '****',
      'oauth_token'        => '****',
      'oauth_token_secret' => '****',
      'oauth_verifier'     => '****',
    );
    access_token($oauth);
    print 'Save access_token: oauth_token=' . $oauth['oauth_token'] . " oauth_token_secret=" . $oauth['oauth_token_secret'] . "\n";

## resource request

    include 'oauth.inc';
    $oauth = array (
      'consumer_key'       => '****',
      'consumer_secret'    => '****',
      'oauth_token'        => '****',
      'oauth_token_secret' => '****',
    );

    $result = resource($oauth, "https://api.domain.com/api/1/user");

    print $result;
