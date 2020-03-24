<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GValueRange
{
  const DIMENSION_ROWS = 'ROWS';
  const DIMENSION_COLUMNS = 'COLUMNS';

  const VALUE_INPUT_RAW = 'RAW';
  const VALUE_INPUT_USER_ENTERED = 'USER_ENTERED';

  private $spreadsheetId;
  private $range;
  private $valueInputOption = self::VALUE_INPUT_USER_ENTERED;
  private $includeValuesInResponse = false;
  private $responseValueRenderOption;
  private $responseDateTimeRenderOption;

  private $majorDimension;
  private $values = [];
  private $batchValue = [];

  /**
   * [__construct Class Constructor.]
   * @date  2020-03-21
   * @param string $spreadsheetId  SpreadSheet ID.
   * @param string $range          Value Range e.g A1:D5
   * @param string $majorDimension How array data (values) should be interpreted.
   */
  public function __construct(string $spreadsheetId, ?string $range=null,
  string $majorDimension=self::DIMENSION_ROWS)
  {
    $this->spreadsheetId = $spreadsheetId;
    $this->range = $range;
    $this->majorDimension = $majorDimension;
  }

  /**
   * [setValueInputOption description]
   * @date   2020-03-22
   * @param  string      $option [description]
   * @return GValueRange         [description]
   */
  public function setValueInputOption(string $option):GValueRange
  {
    $this->valueInputOption = $option;
    return $this;
  }

  /**
   * [values description]
   * @date   2020-03-21
   * @param  array       $array [description]
   * @return GValueRange        [description]
   */
  public function values(array $array):GValueRange
  {
    $this->values = $array;
    return $this;
  }

  /**
   * [addValue description]
   * @date   2020-03-21
   * @param  array       $array [description]
   * @return GValueRange        [description]
   */
  public function addValue(array $array):GValueRange
  {
    $this->values[] = $array;
  }

  /**
   * [addBatchValue description]
   * @date   2020-03-22
   * @param  string      $range [description]
   * @param  array       $array [description]
   * @return GValueRange        [description]
   */
  public function addBatchValue(string $range, array $array):GValueRange
  {
    $this->batchValues[] = [
      'range'          => $range,
      'majorDimension' => $this->majorDimension,
      'values'         => $array
    ];
    return $this;
  }

  /**
   * [getRange description]
   * @date   2020-03-22
   * @return string     [description]
   */
  public function getRange():string
  {
    return $this->range;
  }

  /**
   * [getSpreadSheetId description]
   * @date   2020-03-21
   * @return string     [description]
   */
  public function getSpreadSheetId():string
  {
    return $this->spreadsheetId;
  }

  /**
   * [getValueInputOption description]
   * @date   2020-03-22
   * @return string     [description]
   */
  public function getValueInputOption():string
  {
    return $this->valueInputOption;
  }

  /**
   * [getIncludeValuesInResponse description]
   * @date   2020-03-22
   * @return bool       [description]
   */
  public function getIncludeValuesInResponse():bool
  {
    return $this->includeValuesInResponse;
  }

  /**
   * [batchUpdate description]
   * @date   2020-03-22
   * @return [type] [description]
   */
  public function batchUpdate():?object
  {
    return get_instance()->gsheets->batchUpdate($this);
  }

  /**
   * [update description]
   * @date   2020-03-22
   * @return [type] [description]
   */
  public function update():?object
  {
    return get_instance()->gsheets->update($this);
  }


  /**
   * [toArray description]
   * @date   2020-03-21
   * @return array      [description]
   */
  public function toArray(bool $batchUpdate=false):array
  {
    return $batchUpdate ? [
      'valueInputOption'             => $this->valueInputOption,
      'data'                         => $this->batchValues,
      'includeValuesInResponse'      => $this->includeValuesInResponse,
      'responseValueRenderOption'    => $this->responseValueRenderOption,
      'responseDateTimeRenderOption' => $this->responseDateTimeRenderOption
      ] : [
      'range'          => $this->range,
      'majorDimension' => $this->majorDimension,
      'values'         => $this->values
    ];
  }
}
