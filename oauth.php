<?php
/**
 * PHP OAuth v1.0 light client
 * @category  PHP
 * @link      https://github.com/clementsen/php-oauth-v1-light-client
 * @license   http://www.gnu.org/copyleft/lesser.html Distributed under the Lesser General Public License (LGPL)
 */

function request_token(&$oauth) {
	$result = oauth_request($oauth, $REQUEST_TOKEN);
	parse_str($result);
	$oauth['oauth_token'] = $oauth_token;
	$oauth['oauth_token_secret'] = $oauth_token_secret;
	unset($oauth['oauth_callback']);
}

function access_token(&$oauth) {
	require_param($oauth, 'oauth_verifier');
	require_param($oauth, 'oauth_token');
	require_param($oauth, 'oauth_token_secret');
	$result = oauth_request($oauth, $ACCESS_TOKEN);
	print_r($result);
	parse_str($result);
	$oauth['oauth_token'] = $oauth_token;
	$oauth['oauth_token_secret'] = $oauth_token_secret;
	unset($oauth['oauth_verifier']);
}

function resource($oauth, $url) {
	require_param($oauth, 'oauth_token');
	require_param($oauth, 'oauth_token_secret');
	return oauth_request($oauth, $url);
}

function require_param($oauth, $key) {
	if (!isset($oauth[$key]) ) {
		die ("Missing OAuth parameter: " . $key);
	}
}

# Request resource
function oauth_request($oauth, $URL) {
	require_param($oauth, 'consumer_key');
	require_param($oauth, 'consumer_secret');
	
	$header = oauth_header($oauth, $URL);

	# Request resource
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER,	$header);
	curl_setopt($ch, CURLOPT_URL,		$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT,	30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

	if (preg_match("/oauth_problem=(.*?)&/", $result, $matches, PREG_OFFSET_CAPTURE)) {
		return "oauth_problem=" . $matches[1][0] . "\n";
	}	

	return $result;
}

# Generate Authorization OAuth Header
function oauth_header($oauth, $URL) {
	$oauth['oauth_timestamp'] = isset($oauth['oauth_timestamp']) ? $oauth['oauth_timestamp'] : time();
	$oauth['oauth_nonce'] = isset($oauth['oauth_nonce']) ? $oauth['oauth_nonce'] : crc32(time());

	$params = array(
		'oauth_consumer_key'	=> $oauth['consumer_key'],
		'oauth_nonce'		=> $oauth['oauth_nonce'],
		'oauth_signature_method' => 'HMAC-SHA1',
		'oauth_timestamp'	=> $oauth['oauth_timestamp'],
		'oauth_version'		=> '1.0',
	);

	if (isset($oauth['oauth_token'])) {
		$params['oauth_token'] = $oauth['oauth_token'];
	}

	if (isset($oauth['oauth_callback'])) {
		$params['oauth_callback'] = $oauth['oauth_callback'];
	}
	
	if (isset($oauth['oauth_verifier'])) {
		$params['oauth_verifier'] = $oauth['oauth_verifier'];
	}
	
	# parameters in alphabetical order 
	ksort($params);

	$base_string  = "GET&";
	$base_string .= urlencode($URL);
	$base_string .= '&';
	$base_string .= urlencode(http_build_query($params));

	if (isset($oauth['debug'])) {
		print "signature base string: $base_string\n";
	}

	$key  = urlencode($oauth['consumer_secret']);
	$key .= '&';
	$key .= urlencode($oauth['oauth_token_secret']);

	$signature = base64_encode(hash_hmac("sha1", $base_string, $key, true));

	$params['oauth_signature'] = urlencode($signature);

	$h = array();
	foreach ($params as $name => $value) {
		$h[] = $name . '="' . $value . '"';
	}
	$header[] = 'Authorization: OAuth ' . implode(',', $h);

	if (isset($oauth['debug'])) {
		print "Authorization header: " . $header[0] . "\n";
	}
	
	return $header;
}
?>
