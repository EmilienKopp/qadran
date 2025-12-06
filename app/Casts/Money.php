<?php

namespace App\Casts;


use \Money\Money as PHPMoney;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class Money implements CastsAttributes
{
  public function get($model, string $key, $value, array $attributes): ?PHPMoney
  {
      if ($value === null) {
          return null;
      }

      if(!$model->hasAttribute('amount') || !$model->hasAttribute('currency')) {
          throw new InvalidArgumentException("The model does not have the required attributes for Money casting.");
      }

      return new PHPMoney($value, new \Money\Currency($attributes['currency']));
  }

  public function set($model, string $key, $value, array $attributes): ?array
  {
      if ($value === null) {
          return null;
      }

      if (!$value instanceof PHPMoney) {
          throw new InvalidArgumentException("The given value is not an instance of Money.");
      }

      return [
          'amount' => $value->getAmount(),
          'currency' => $value->getCurrency()->getCode(),
      ];
  }
}