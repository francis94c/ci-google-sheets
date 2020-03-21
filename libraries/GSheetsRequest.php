<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GSheetsRequest
{
  /**
   * [GET description]
   * @var string
   */
  const GET  = 'GET';

  /**
   * [POST description]
   * @var string
   */
  const POST = 'POST';

  /**
   * [private description]
   * @var [type]
   */
  private $verb;

  /**
   * [__construct description]
   * @date  2020-03-14
   * @param string     $verb [description]
   */
  public function __construct(string $verb)
  {
    $this->verb = $verb;
  }

  /**
   * [__invoke description]
   * @date   2020-03-14
   * @param  string     $url    [description]
   * @param  array      $header [description]
   * @param  [type]     $body   [description]
   * @return [type]             [description]
   */
  public function __invoke(string $url, array $header=[], array $body=null):array
  {
    if ($body != null) $body = json_encode($body);

    $ch = curl_init($url);

    // Defaults.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    if (ENVIRONMENT == 'development') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    }

    // Header.
    $header[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CI GSheets API');

    // Request Method and Body.
    if ($this->verb == self::POST) {
      curl_setopt($ch, CURLOPT_POST, true);
      if ($body) curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    // Exec.
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [$code, $response];
  }
}
