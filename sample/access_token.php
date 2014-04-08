<?php

$ACCESS_TOKEN  = 'https://api.domain.com/oauth/access_token';
include '../oauth.php';
$oauth = array (
  'consumer_key'       => '****',
  'consumer_secret'    => '****',
  'oauth_token'        => '****',
  'oauth_token_secret' => '****',
  'oauth_verifier'     => '****',
);
access_token($oauth);
print 'Save access_token: oauth_token=' . $oauth['oauth_token'] . " oauth_token_secret=" . $oauth['oauth_token_secret'] . "\n";

?>
