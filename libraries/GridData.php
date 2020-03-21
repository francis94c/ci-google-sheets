<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GridData
{
  private $startRow;
  private $startColumn;
  private $rowData = [];
  private $rowDataIndex = -1;

  /**
   * [__construct description]
   * @date  2020-03-19
   * @param int $startRow    [description]
   * @param int $startColumn [description]
   */
  public function __construct(int $startRow, int $startColumn)
  {
    $this->startRow = $startRow;
    $this->startColumn = $startColumn;
  }

  /**
   * [rowItems description]
   * @date   2020-03-19
   * @param  [type]     $items [description]
   * @return GridData          [description]
   */
  public function rowItems(...$items):GridData
  {
    foreach ($items as $item) {
      if (gettype($item) == 'object' && get_class($item) == 'GCellData') {
        $this->rowData[] = $item->toArray();
        continue;
      }
      $this->rowData[] = (new GCellData($item))->toArray();
    }
    return $this;
  }

  /**
   * [getRowData description]
   * @date   2020-03-21
   * @param  int        $index [description]
   * @return GCellData         [description]
   */
  public function getRowData(int $index):GCellData
  {
    return gcell_data()->fromArray($this->rowData[$index]);
  }

  /**
   * [getNextRowData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getNextRowData():?GCellData
  {
    if ($this->rowDataIndex + 1 > count($this->rowDataIndex) - 1) return null;
    return gcell_data()->fromArray($this->rowData[++$this->rowDataIndex]);
  }

  /**
   * [getPreviousRowData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getPreviousRowData():?GCellData
  {
    if (count($this->rowData) == 0 || $this->rowDataIndex - 1 < 0) return null;
    return gcell_data()->fromArray($this->rowData[--$this->rowDataIndex]);
  }

  /**
   * [getFirstRowData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getFirstRowData():?GCellData
  {
    if (count($this->rowData) == 0) return null;
    $this->rowDataIndex = 0;
    return gcell_data()->fromArray($this->rowData[$this->rowDataIndex]);
  }

  /**
   * [getLastRowData description]
   * @date   2020-03-21
   * @return [type]     [description]
   */
  public function getLastRowData():?GCellData
  {
    if (count($this->rowData) == 0) return null;
    $this->rowDataIndex = count($this->rowData) - 1;
    return gcell_data()->fromArray($this->rowData[$this->rowDataIndex]);
  }

  /**
   * [fromArray description]
   * @date   2020-03-21
   * @param  array      $array [description]
   * @return GridData          [description]
   */
  public function fromArray(array $array):GridData
  {
    $this->startRow = $array['startRow'];
    $this->startColumn = $array['startColumn'];
    foreach ($array['rowData']['values'] as $rowData) $this->rowData[] = $rowData;
  }

  /**
   * [toArray description]
   * @date   2020-03-19
   * @return array      [description]
   */
  public function toArray():array
  {
    return [
      'startRow'    => $this->startRow,
      'startColumn' => $this->startColumn,
      'rowData'     => [
        'values' => $this->rowData
      ]
    ];
  }
}
