<?php

include '../oauth.inc';
$oauth = array (
  'consumer_key'       => '****',
  'consumer_secret'    => '****',
  'oauth_token'        => '****',
  'oauth_token_secret' => '****',
);

$result = resource($oauth, "https://api.domain.com/api/1/user");

print $result;

?>
