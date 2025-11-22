<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

class DTO implements Arrayable
{
    public function toArray(): array
    {
        $data = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value instanceof Arrayable) {
                $data[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$key] = array_map(function ($item) {
                    return $item instanceof Arrayable ? $item->toArray() : $item;
                }, $value);
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}