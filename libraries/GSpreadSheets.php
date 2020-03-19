<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GSpreadSheets
{
  const AUTH_URL  = 'https://accounts.google.com/o/oauth2/auth';
  const TOKEN_URL = 'https://www.googleapis.com/oauth2/v4/token';
  const API       = 'https://www.googleapis.com/gmail/v1/users/';
  const HTTP_CODE = 'http_code';
  const PACKAGE   = 'francis94c/ci-gmail';
  private $clientId;
  private $clientSecret;
  private $redirectUri = 'urn:ietf:wg:oauth:2.0:oob';
  private $token;
  private $userAgent = 'CodeIgniter GMail API';
  private $lastResponse;
  private $lastResponseCode;

  /**
   * [init Initialize library with cofigs. Can be called multiple times to set
   *       config items]
   * @param array $config Associative Config Array.
   */
  public function init(array $config):void {
    $this->clientId = $config['client_id'] ?? $this->clientId;
    $this->clientSecret = $config['client_secret'] ?? $this->clientSecret;
    $this->redirectUri = $config['redirect_uri'] ?? $this->redirectUri;
  }
}
