<?php

$REQUEST_TOKEN = 'https://api.domain.com/oauth/request_token';
include '../oauth.inc';
$oauth = array (
  'consumer_key'    => '****',
  'consumer_secret' => '****',
);
$oauth['oauth_callback'] = 'http://www.clientdomain.com/callback/test';
request_token($oauth);

print 'Save request_token: oauth_token=' . $oauth['oauth_token'] . " oauth_token_secret=" . $oauth['oauth_token_secret'] . "\n";
print 'Redirect user to: https://api.domain.com/oauth/authorize?oauth_token=' . $oauth['oauth_token'];

?>
