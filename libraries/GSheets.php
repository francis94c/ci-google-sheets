<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GSheets
{
  const AUTH_URL  = 'https://accounts.google.com/o/oauth2/auth';
  const TOKEN_URL = 'https://www.googleapis.com/oauth2/v4/token';
  const API       = 'https://sheets.googleapis.com/v4/spreadsheets/';
  const HTTP_CODE = 'http_code';
  const PACKAGE   = 'francis94c/ci-google-sheets';
  private $clientId;
  private $clientSecret;
  private $redirectUri = 'urn:ietf:wg:oauth:2.0:oob';
  private $accessToken;
  private $userAgent = 'CodeIgniter Google Sheets API';
  private $lastResponse;
  private $lastResponseCode;

  /**
   * [__construct description]
   * @date  2020-03-19
   * @param [type]     $params [description]
   */
  public function __construct(?array $params=null)
  {
    splint_autoload_register(self::PACKAGE);

    get_instance()->load->splint(self::PACKAGE, '%gsheets');

    if ($params != null) $this->init($params);
  }

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

  /**
   * [setAccessToken description]
   * @date  2020-03-19
   * @param string     $accessToken [description]
   */
  public function setAccessToken(string $accessToken):void {
    $this->accessToken = $accessToken;
  }

  /**
   * [getAuthorizeUrl description]
   * @date   2020-03-19
   * @param  [type]     $redirectUri  [description]
   * @param  string     $responseType [description]
   * @param  string     $accessType   [description]
   * @param  boolean    $prompt       [description]
   * @return string                   [description]
   */
  public function getAuthorizeUrl(string $redirectUri=null,
  string $responseType='code', string $accessType='offline', bool $prompt=false):string
  {
    $redirectUri = $redirectUri ?? $this->redirectUri;
    $params = [
      'client_id'     => $this->clientId,
      'redirect_uri'  => $redirectUri,
      'scope'         => 'https://www.googleapis.com/auth/spreadsheets',
      'response_type' => $responseType,
      'access_type'   => $accessType
    ];
    if ($prompt) $params['prompt'] = 'consent';
    return self::AUTH_URL . $this->build_url_query($params, false);
  }

  /**
   * [getToken description]
   * @param  string $code [description]
   * @return [type]       [description]
   */
  public function getToken(string $code, string $redirectUri=null):?object
  {
    $redirectUri = $redirectUri ?? $this->redirectUri;
    $ch = curl_init(self::TOKEN_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    if (ENVIRONMENT == 'development') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    $body = http_build_query([
      'code'          => $code,
      'client_id'     => $this->clientId,
      'client_secret' => $this->clientSecret,
      'redirect_uri'  => $this->redirectUri,
      'grant_type'    => 'authorization_code'
    ]);
    $header = [
      'Content-Type: application/x-www-form-urlencoded',
      'Content-Length: ' . strlen($body)
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    // Request Method and Body.
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $this->lastResponse = $response;
    $this->lastResponseCode = $code;
    if ($response !== false) return $this->process_response($code, $response);
    return null;
  }

  /**
   * [refreshAccessToken description]
   * @param  string $refreshToken [description]
   * @return [type]               [description]
   */
  public function refreshAccessToken(string $refreshToken):?object
  {
    $ch = curl_init(self::TOKEN_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    if (ENVIRONMENT == 'development') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }
    $body = http_build_query([
      'refresh_token' => $refreshToken,
      'client_id'     => $this->clientId,
      'client_secret' => $this->clientSecret,
      'grant_type'    => 'refresh_token'
    ]);
    $header = [
      'Content-Type: application/x-www-form-urlencoded',
      'Content-Length: ' . strlen($body)
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
    // Request Method and Body.
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    // Exec.
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $this->lastResponse = $response;
    $this->lastResponseCode = $code;
    if ($response !== false) return $this->process_response($code, $response);
    return null;
  }

  /**
   * [build_url_query description]
   * @date   2020-03-19
   * @param  array      $params    [description]
   * @param  boolean    $urlEncode [description]
   * @return [type]                [description]
   */
  private function build_url_query(array $params, bool $urlEncode=true):?string {
    if ($params == null) return null;
    $queryString = '?';
    foreach($params as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $value) {
          $queryString .= $key."=".($urlEncode ? rawurlencode($value) : $value)."&";
        }
        continue;
      }

      $queryString .= $key."=".($urlEncode ? rawurlencode($val) : $val)."&";
    }
    return substr($queryString, 0, strlen($queryString) - 1);
  }

  /**
   * [createSpreadSheet description]
   * @date   2020-03-19
   * @param  GSpreadSheet $spreadSheet [description]
   * @return [type]                    [description]
   */
  public function createSpreadSheet(GSpreadSheet $spreadSheet):?GSpreadSheet
  {
    list($code, $response) = (new GSheetsRequest(GSheetsRequest::POST))(
      self::API,
      ["Authorization: Bearer $this->accessToken"],
      $spreadSheet->toArray()
    );

    $this->lastResponseCode = $code;
    $this->lastResponse = $response;

    if ($code == 200) {
      return gspreadsheet()->fromJson($response);
    }

    return null;
  }

  /**
   * [batchUpdate description]
   * @date   2020-03-22
   * @param  GValueRange $value [description]
   * @return [type]             [description]
   */
  public function batchUpdate(GValueRange $value):?object
  {
    list($code, $response) = (new GSheetsRequest(GSheetsRequest::POST))(
      self::API . $value->getSpreadSheetId() . '/values:batchUpdate',
      ["Authorization: Bearer $this->accessToken"],
      $value->toArray(true)
    );
    $this->lastResponseCode = $code;
    $this->lastResponse = $response;
    if ($code == 200) return $this->process_response($code, $response);
    return null;
  }

  /**
   * [update description]
   * @date   2020-03-22
   * @param  GValueRange $value [description]
   * @return [type]             [description]
   */
  public function update(GValueRange $value):?object
  {
    list($code, $response) = (new GSheetsRequest(GSheetsRequest::POST))(
      self::API . $value->getSpreadSheetId() . "/values/{$value->getRange()}" . $this->build_url_query([
        'valueInputOption'        => $value->getValueInputOption(),
        'includeValuesInResponse' => $value->getIncludeValuesInResponse()
      ]),
      ["Authorization: Bearer $this->accessToken"],
      $value->toArray()
    );
    $this->lastResponseCode = $code;
    $this->lastResponse = $response;
    if ($code == 200) return $this->process_response($code, $response);
    return null;
  }

  /**
   * [getLastResponse description]
   * @date   2020-03-15
   * @return string     [description]
   */
  public function getLastResponse():string
  {
    return $this->lastResponse;
  }

  /**
   * [getLastResponseCode description]
   * @date   2020-03-15
   * @return string     [description]
   */
  public function getLastResponseCode():string
  {
    return $this->lastResponseCode;
  }

  /**
   * [process_response description]
   * @param  int    $code     [description]
   * @param  string $response [description]
   * @return [type]           [description]
   */
  private function process_response(int $code, string $response):?object {
    $response = json_decode($response);
    $response->{self::HTTP_CODE} = $code;
    return $response;
  }
}
