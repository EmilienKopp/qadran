<?php

namespace App\Contracts;

interface BaseDataAccess
{
  public function find($id);
  public function firstWhere($column, $operator, $value = null);
}