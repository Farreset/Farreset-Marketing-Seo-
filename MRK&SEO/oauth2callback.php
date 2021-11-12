<?php

// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

// Start a session to persist credentials.
session_start();

// Create the client object and set the authorization configuration
// from the client_secrets.json you downloaded from the Developers Console.
$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/account-credentials.json');
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/nuestros_clientes/administracio/clientes/oauth2callback.php');
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);

// Handle authorization flow from the server.
if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  //$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/nuestros_clientes/administracio/clientes/analytics.php';
  $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/nuestros_clientes/administracio/clientes/clientes_informe.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

?>