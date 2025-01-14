<?php 

namespace App\Traits;

trait EnumHelper
{
  public static function toArray(): array
  {
    return array_column(static::cases(), 'value');
  }
}