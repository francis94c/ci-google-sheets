<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GSpreadSheet
{
  /**
   * [private description]
   * @var [type]
   */
  private $id;

  /**
   * [private description]
   * @var [type]
   */
  private $sheets = [];

  /**
   * [private description]
   * @var [type]
   */
  private $properties = [];
  private $sheetsIndex = -1;

  /**
   * [setTitle description]
   * @date   2020-03-19
   * @param  string       $title [description]
   * @return GSpreadSheet        [description]
   */
  public function setTitle(string $title):GSpreadSheet
  {
    $this->properties['title'] = $title;
    return $this;
  }

  /**
   * [getTitle description]
   * @date   2020-03-20
   * @return string     [description]
   */
  public function getTitle():string
  {
    return $this->properties['title'];
  }

  /**
   * [getProperties description]
   * @date   2020-03-20
   * @return array      [description]
   */
  public function getProperties():array
  {
    return $this->properties;
  }

  /**
   * [getId description]
   * @date   2020-03-20
   * @return string     [description]
   */
  public function getId():string
  {
    return $this->id;
  }

  /**
   * [addSheet description]
   * @date   2020-03-19
   * @param  GSheet       $sheets [description]
   * @return GSpreadSheet         [description]
   */
  public function addSheet(GSheet ...$sheets):GSpreadSheet
  {
    foreach ($sheets as $sheet) {
      $this->sheets[] = $sheet->toArray();
    }
    return $this;
  }

  /**
   * [getSheetsCount description]
   * @date   2020-03-20
   * @return GSpreadSheet [description]
   */
  public function getSheetsCount():GSpreadSheet
  {
    return count($this->sheets);
  }

  /**
   * [getSheet description]
   * @date   2020-03-20
   * @param  int        $index [description]
   * @return GSheet            [description]
   */
  public function getSheet(int $index):GSheet
  {
    return gsheet()->fromArray($this->sheets[$index]);
  }

  /**
   * [getNextSheet description]
   * @date   2020-03-20
   * @return [type]     [description]
   */
  public function getNextSheet():?GSheet
  {
    if ($this->sheetsIndex + 1 > count($this->sheets) - 1) return null;
    return gsheet()->fromArray($this->sheets[++$this->sheetsIndex]);
  }

  /**
   * [getPreviousSheet description]
   * @date   2020-03-20
   * @return [type]     [description]
   */
  public function getPreviousSheet():?GSheet
  {
    if (count($this->sheets) == 0 || $this->sheetsIndex - 1 < 0) return null;
    return gsheet()->fromArray($this->sheets[--$this->sheetsIndex]);
  }

  /**
   * [getFirstSheet description]
   * @date   2020-03-20
   * @return [type]     [description]
   */
  public function getFirstSheet():?GSheet
  {
    if (count($this->sheets) == 0) return null;
    $this->sheetsIndex = 0;
    return gsheet()->fromArray($this->sheets[$this->sheetsIndex]);
  }

  /**
   * [getLastSheet description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getLastSheet():?GSheet
  {
    if (count($this->sheets) == 0) return null;
    $this->sheetsIndex = count($this->sheets) - 1;
    return gsheet()->fromArray($this->sheets[$this->sheetsIndex]);
  }

  /**
   * [fromJson description]
   * @date   2020-03-20
   * @param  string       $json [description]
   * @return GSpreadSheet       [description]
   */
  public function fromJson(string $json):GSpreadSheet
  {
    $spreadsheet = json_decode($json, true);
    if (!$spreadsheet) throw new Exception('Cannot create spreadsheet from invalid json');

    return $this->fromArray($spreadsheet);
  }

  /**
   * [fromArray description]
   * @date   2020-03-21
   * @param  array        $array [description]
   * @return GSpreadSheet        [description]
   */
  public function fromArray(array $array):GSpreadSheet
  {
    $this->id = $array['spreadsheetId'];
    $this->properties = $array['properties'];
    foreach ($array['sheets'] as $sheet) $this->sheets[] = $sheet;

    return $this;
  }

  /**
   * [create description]
   * @date   2020-03-22
   * @return GSpreadSheet [description]
   */
  public function create():GSpreadSheet
  {
    return get_instance()->gsheets->createSpreadSheet($this);
  }

  /**
   * [toArray description]
   * @date   2020-03-19
   * @return array      [description]
   */
  public function toArray():array
  {
    return [
      'properties' => $this->properties,
      'sheets'     => $this->sheets
    ];
  }
}
