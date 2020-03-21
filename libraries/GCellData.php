<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GCellData
{
  /**
   * [private description]
   * @var array
   */
  private $userEnteredValue = [];

  /**
   * [private description]
   * @var [type]
   */
  private $effectiveValue;

  /**
   * [private description]
   * @var [type]
   */
  private $formattedValue;

  /**
   * [private description]
   * @var [type]
   */
  private $userEnteredFormat;

  /**
   * [private description]
   * @var [type]
   */
  private $effectiveFormat;

  /**
   * [private description]
   * @var [type]
   */
  private $hyperlink;

  /**
   * [private description]
   * @var [type]
   */
  private $note;

  /**
   * [__construct description]
   * @date  2020-03-19
   * @param [type]     $value [description]
   */
  public function __construct($value=null)
  {
    if ($value === null) return;

    $type = gettype($value);
    switch ($type) {
      case 'string':
        $this->userEnteredValue[substr($value, 0, 1) == '=' ? 'formulaValue' : 'stringValue'] = $value;
        break;
      case 'integer':
        $this->userEnteredValue['numberValue'] = $value;
        break;
      case 'boolean':
        $this->userEnteredValue['boolValue'] = $value;
        break;
    }
  }

  /**
   * [getValueType description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getValueType():?string
  {
    return array_keys($this->userEnteredValue)[0] ?? null;
  }

  /**
   * [getValue description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getValue()
  {
    return array_values($this->userEnteredValue)[0] ?? null;
  }

  /**
   * [setFormulaValue description]
   * @date   2020-03-19
   * @param  string     $value [description]
   * @return GCellData         [description]
   */
  public function setFormulaValue(string $value):GCellData
  {
    $this->userEnteredValue['formulaValue'] = $value;
    return $this;
  }

  /**
   * [getEffectiveValue description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getEffectiveValue():?array
  {
    return $this->effectiveValue;
  }

  /**
   * [getFormattedValue description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getFormattedValue():?string
  {
    return $this->formattedValue;
  }

  /**
   * [getUserEnteredFormat description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getUserEnteredFormat():?array
  {
    return $this->userEnteredFormat;
  }

  /**
   * [getEffectiveFormat description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getEffectiveFormat():?array
  {
    return $this->effectiveValue;
  }

  /**
   * [getHyperlink description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getHyperlink():?string
  {
    return $this->hyperlink;
  }

  /**
   * [setNote description]
   * @date   2020-03-19
   * @param  string     $note [description]
   * @return GCellData        [description]
   */
  public function setNote(string $note):GCellData
  {
    $this->note = $note;
    return $this;
  }

  /**
   * [getNote description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getNote():?string
  {
    return $this->note;
  }

  /**
   * [fromArray description]
   * @date   2020-03-21
   * @param  array      $array [description]
   * @return GCellData         [description]
   */
  public function fromArray(array $array):GCellData
  {
    $this->userEnteredValue = $array['userEnteredValue'] ?? [];
    $this->effectiveValue = $array['effectiveValue'] ?? null;
    $this->formattedValue = $array['formattedValue'] ?? null;
    $this->userEnteredFormat = $array['userEnteredFormat'] ?? null;
    $this->hyperlink = $array['hyperlink'] ?? null;
    $this->note = $array['note'] ?? null;
  }

  /**
   * [toArray description]
   * @date   2020-03-19
   * @return array      [description]
   */
  public function toArray():array
  {
    $data = [
      'userEnteredValue' => $this->userEnteredValue
    ];

    if ($this->note) $data['note'] = $this->note;

    return $data;
  }
}
