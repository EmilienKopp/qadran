<?php

namespace App\DataAccess\Local;

use App\Contracts\BaseDataAccess;

abstract class BaseLocalAccess implements BaseDataAccess
{
  protected string $model;

  public function find($id)
  {
    return $this->model::find($id);
  }

  public function firstWhere($column, $operator, $value = null)
  {
    return $this->model::firstWhere($column, $operator, $value);
  }

  public function all()
  {
    return $this->model::all();
  }

  public function where($column, $operator, $value = null)
  {
    return $this->model::where($column, $operator, $value)->get();
  }

  public function query()
  {
    return $this->model::query();
  }
}