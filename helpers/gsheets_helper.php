<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('gsheet')) {
  function gsheet():GSheet
  {
    return new GSheet();
  }
}

if (!function_exists('gcell_data')) {
  function gcell_data($value):GCellData
  {
    return new GCellData($value);
  }
}

if (!function_exists('grid_data')) {
  function grid_data(int $startRow, int $startColumn):GridData
  {
    return new GridData($startRow, $startColumn);
  }
}

if (!function_exists('gspreadsheet')) {
  function gspreadsheet():GSpreadSheet
  {
    return new GSpreadSheet();
  }
}

if (!function_exists('gvalue_range')) {
  function gvalue_range(string $spreadsheetId, ?string $range=null, string $majorDimension=GValueRange::DIMENSION_ROWS):GValueRange
  {
    return new GValueRange($spreadsheetId, $range, $majorDimension);
  }
}
