<?php
require_once('./php_conf.inc');
require_once('./resource/tmhOAuth/tmhOAuth.php');
require_once('./resource/tmhOAuth/tmhUtilities.php');
$tmhOAuth = new tmhOAuth(array(
	'consumer_key'=>consumer_key,
	'consumer_secret'=>consumer_secret,
));

$here = tmhUtilities::php_self();
session_start();

function outputError($tmhOAuth) {
  echo 'Error: ' . $tmhOAuth->response['response'] . PHP_EOL;
  tmhUtilities::pr($tmhOAuth);
}

// reset request?
if ( isset($_REQUEST['wipe'])) {
  session_destroy();
  header("Location: {$here}");

// already got some credentials stored?
} elseif ( isset($_SESSION['access_token']) ) {
  $tmhOAuth->config['user_token']  = $_SESSION['access_token']['oauth_token'];
  $tmhOAuth->config['user_secret'] = $_SESSION['access_token']['oauth_token_secret'];

  $code = $tmhOAuth->request('GET', $tmhOAuth->url('1/account/verify_credentials'));
  if ($code == 200) {
    $resp = json_decode($tmhOAuth->response['response']);
	//　ログイン済み
	$_SESSION['response'] = $resp;
	include('./html/dashboard.inc');
  } else {
    outputError($tmhOAuth);
  }
// we're being called back by Twitter
} elseif (isset($_REQUEST['oauth_verifier'])) {
  $tmhOAuth->config['user_token']  = $_SESSION['oauth']['oauth_token'];
  $tmhOAuth->config['user_secret'] = $_SESSION['oauth']['oauth_token_secret'];

  $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/access_token', ''), array(
    'oauth_verifier' => $_REQUEST['oauth_verifier']
  ));

  if ($code == 200) {
    $_SESSION['access_token'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
    unset($_SESSION['oauth']);
    header("Location: {$here}");
  } else {
    outputError($tmhOAuth);
  }
// start the OAuth dance
} elseif ( isset($_REQUEST['authenticate']) || isset($_REQUEST['authorize']) ) {
  $callback = isset($_REQUEST['oob']) ? 'oob' : $here;

  $params = array(
    'oauth_callback'     => $callback
  );

  if (isset($_REQUEST['force_write'])) :
    $params['x_auth_access_type'] = 'write';
  elseif (isset($_REQUEST['force_read'])) :
    $params['x_auth_access_type'] = 'read';
  endif;

  $code = $tmhOAuth->request('POST', $tmhOAuth->url('oauth/request_token', ''), $params);

  if ($code == 200) {
    $_SESSION['oauth'] = $tmhOAuth->extract_params($tmhOAuth->response['response']);
    $method = isset($_REQUEST['authenticate']) ? 'authenticate' : 'authorize';
    $force  = isset($_REQUEST['force']) ? '&force_login=1' : '';
    $authurl = $tmhOAuth->url("oauth/{$method}", '') .  "?oauth_token={$_SESSION['oauth']['oauth_token']}{$force}";
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: {$authurl}");
  } else {
    outputError($tmhOAuth);
  }
}

if (empty($_SESSION['access_token'])) include('./html/login.inc');