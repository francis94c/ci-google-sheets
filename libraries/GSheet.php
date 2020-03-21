<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GSheet
{
  const TYPE_UNSPECIFIED = 'SHEET_TYPE_UNSPECIFIED';
  const TYPE_GRID = 'GRID';
  const TYPE_OBJECT = 'OBJECT';

  private $properties = [
    'sheetType' => self::TYPE_GRID
  ];
  private $data = [];
  private $dataIndex = -1;

  /**
   * [setId description]
   * @date   2020-03-19
   * @param  int    $id [description]
   * @return GSheet     [description]
   */
  public function setId(int $id):GSheet
  {
    $this->properties['sheetId'] = $id;
    return $this;
  }

  /**
   * [getId description]
   * @date   2020-03-19
   * @return int        [description]
   */
  public function getId():int
  {
    return $this->properties['sheetId'] ?? 0;
  }

  /**
   * [setTitle description]
   * @date   2020-03-19
   * @param  string $title [description]
   * @return GSheet        [description]
   */
  public function setTitle(string $title):GSheet
  {
    $this->properties['title'] = $title;
    return $this;
  }

  /**
   * [getTitle description]
   * @date   2020-03-19
   * @return string [description]
   */
  public function getTitle():string
  {
    return $this->properties['title'];
  }

  /**
   * [setIndex description]
   * @date   2020-03-19
   * @param  int        $index [description]
   * @return GSheet            [description]
   */
  public function setIndex(int $index):GSheet
  {
    $this->properties['index'] = $index;
    return $this;
  }

  /**
   * [getIndex description]
   * @date   2020-03-19
   * @return int        [description]
   */
  public function getIndex():int
  {
    return $this->properties['index'];
  }

  /**
   * [setType description]
   * @date   2020-03-19
   * @param  string     $type [description]
   * @return GSheet           [description]
   */
  public function setType(string $type):GSheet
  {
    switch ($type) {
      case self::TYPE_UNSPECIFIED:
      case self::TYPE_GRID:
      case self::TYPE_OBJECT:
        $this->properties['sheetType'] = $type;
        return $this;
      default:
        throw new Exception("UnSupported Sheet Type");
    }
  }

  /**
   * [getGridDataCount description]
   * @date   2020-03-21
   * @return int        [description]
   */
  public function getGridDataCount():int
  {
    return count($this->data);
  }

  /**
   * [getGridData description]
   * @date   2020-03-21
   * @param  int        $index [description]
   * @return GridData          [description]
   */
  public function getGridData(int $index):GridData
  {
    return grid_data()->fromArray($this->data[$index]);
  }

  /**
   * [getNextGridData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getNextGridData():?GridData
  {
    if ($this->dataIndex + 1 > count($this->data) - 1) return null;
    return grid_data()->fromArray($this->data[++$this->dataIndex]);
  }

  /**
   * [getPreviousGridData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getPreviousGridData():?GridData
  {
    if (count($this->data) == 0 || $this->dataIndex - 1 < 0) return null;
    return grid_data()->fromArray($this->data[--$this->dataIndex]);
  }

  /**
   * [getFirstGridData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getFirstGridData():?GridData
  {
    if (count($this->data) == 0) return null;
    $this->dataIndex = 0;
    return grid_data()->fromArray($this->data[$this->dataIndex]);
  }

  /**
   * [getLastGridData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getLastGridData():?GridData
  {
    if (count($this->data) == 0) return null;
    $this->dataIndex = count($this->data) - 1;
    return grid_data()->fromArray($this->data[$this->dataIndex]);
  }

  /**
   * [getType description]
   * @date   2020-03-19
   * @return string     [description]
   */
  public function getType():string
  {
    return $this->properties['sheetType'];
  }

  /**
   * [addGridData description]
   * @date   2020-03-19
   * @param  GridData   $gridData [description]
   * @return GSheet               [description]
   */
  public function addGridData(GridData $gridData):GSheet
  {
    $this->data[] = $gridData->toArray();
    return $this;
  }

  /**
   * [fromJson description]
   * @date   2020-03-20
   * @param  string     $sheet [description]
   * @return GSheet            [description]
   */
  public function fromJson(string $sheet):GSheet
  {
    $sheet = json_decode($sheet, true);
    if (!$sheet) throw new Exception('Cannot create sheet from invalid json');

    return $this->fromArray($sheet);
  }

  /**
   * [fromArray description]
   * @date   2020-03-20
   * @param  array      $array [description]
   * @return GSheet            [description]
   */
  public function fromArray(array $array):GSheet
  {
    $this->properties = $array['properties'];
    foreach ($array['data'] ?? [] as $data) $this->data[] = $data;

    return $this;
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
      'data'       => $this->data
    ];
  }
}
