<?php
require_once("OAuth.php");

class RightSignature_OLD_DEPRECATED {
  public $base_url = "https://rightsignature.com";
  public $secure_base_url = "https://rightsignature.com";
  public $oauth_callback = "oob";
  public $consumer;
  public $request_token;
  public $access_token;
  public $oauth_verifier;
  public $signature_method;
  public $request_token_path;
  public $access_token_path;
  public $authorize_path;
  public $debug = false;
  
  function __construct($consumer_key, $consumer_secret, $oauth_callback = NULL) {
    
    if($oauth_callback) {
      $this->oauth_callback = $oauth_callback;
    }
    
    $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret, $this->oauth_callback);
    $this->signature_method = new OAuthSignatureMethod_HMAC_SHA1();
    $this->request_token_path = $this->secure_base_url . "/oauth/request_token";
    $this->access_token_path = $this->secure_base_url . "/oauth/access_token";
    $this->authorize_path = $this->secure_base_url . "/oauth/authorize";
    
  }

  function getRequestToken() {
    $consumer = $this->consumer;
    $request = OAuthRequest::from_consumer_and_token($consumer, NULL, "GET", $this->request_token_path);
    $request->set_parameter("oauth_callback", $this->oauth_callback);
    $request->sign_request($this->signature_method, $consumer, NULL);
    $headers = Array();
    $url = $request->to_url();
    $response = $this->httpRequest($url, $headers, "GET");
    parse_str($response, $response_params);
    $this->request_token = new OAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1);
  }

  function generateAuthorizeUrl() {
    $consumer = $this->consumer;
    $request_token = $this->request_token;
    return $this->authorize_path . "?oauth_token=" . $request_token->key;
  }

  function getAccessToken() {
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->request_token, "GET", $this->access_token_path);
    $request->set_parameter("oauth_verifier", $this->oauth_verifier);
    $request->sign_request($this->signature_method, $this->consumer, $this->request_token);
    $headers = Array();
    $url = $request->to_url();
    $response = $this->httpRequest($url, $headers, "GET");
    parse_str($response, $response_params);
    if($this->debug) {
      echo $response . "\n";
    }
    $this->access_token = new OAuthConsumer($response_params['oauth_token'], $response_params['oauth_token_secret'], 1);
  }
  
  /*
  function getDocuments() {
    $url = $this->base_url . "/api/documents.xml";
    echo "Getting documents...\n";
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://rightsignature.com");
    if ($this->debug) {
      echo $auth_header . "\n";
    }
    $response = $this->httpRequest($url, $auth_header, "GET");
    return $response;
  }
  */
  
  function get($path) {
    $url = $this->base_url . $path;
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "GET", $url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    $auth_header = $request->to_header("https://rightsignature.com");
    if ($this->debug) {
      echo $auth_header . "\n";
    }
    $response = $this->httpRequest($url, $auth_header, "GET");
    return $response;
  }
  
  /*
  function addUser($uname, $email) {
    $url = $this->base_url . "/api/users.xml";
    echo "Adding user...\n";
    $xml = "<?xml version='1.0' encoding='UTF-8'?><user><name>$uname</name><email>$email</email></user>";
    echo $xml;
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    
    $auth_header = $request->to_header("https://rightsignature.com");
    # Make sure there is a space and not a comma after OAuth
    $auth_header = preg_replace("/Authorization\: OAuth\,/", "Authorization: OAuth ", $auth_header);
    # Make sure there is a space between OAuth attribute
    $auth_header = preg_replace('/\"\,/', '", ', $auth_header);
    if ($this->debug) {
      echo $auth_header . "\n";
    }
    
    $response = $this->httpRequest($url, $auth_header, "POST", $xml);
    return $response;
  }
  
  function testPost() {
    $url = $this->base_url . "/api/test.xml";
    $xml = "<?xml version='1.0' encoding='UTF-8'?><testnode>Hello World!</testnode>";
    echo $xml;
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    
    $auth_header = $request->to_header("https://rightsignature.com");
    # Make sure there is a space and not a comma after OAuth
    $auth_header = preg_replace("/Authorization\: OAuth\,/", "Authorization: OAuth ", $auth_header);
    # Make sure there is a space between OAuth attribute
    $auth_header = preg_replace('/\"\,/', '", ', $auth_header);
    if ($this->debug) {
      echo $auth_header . "\n";
    }
    
    $response = $this->httpRequest($url, $auth_header, "POST", $xml);
    return $response;
  } */
  
  function post($path, $xml) {
    $url = $this->base_url . $path;
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->access_token, "POST", $url);
    $request->sign_request($this->signature_method, $this->consumer, $this->access_token);
    
    $auth_header = $request->to_header("https://rightsignature.com");
    # Make sure there is a space and not a comma after OAuth
    $auth_header = preg_replace("/Authorization\: OAuth\,/", "Authorization: OAuth ", $auth_header);
    # Make sure there is a space between OAuth attribute
    $auth_header = preg_replace('/\"\,/', '", ', $auth_header);
    if ($this->debug) {
      echo $auth_header . "\n";
    }
    
    $response = $this->httpRequest($url, $auth_header, "POST", $xml);
    return $response;
  }
  
  function httpRequest($url, $auth_header, $method, $body = NULL) {
    if (!$method) {
      $method = "GET";
    };

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header)); // Set the headers.

    if ($body) {
      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header, "Content-Type: text/xml;charset=utf-8"));   
    }

    $data = curl_exec($curl);
    if ($this->debug) {
      echo $data . "\n";
    }
    curl_close($curl);
    return $data; 
  }

}
