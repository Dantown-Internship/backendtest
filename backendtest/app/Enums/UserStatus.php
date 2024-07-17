<?php
namespace App\Enums;

use App\Traits\EnumOptions;

enum UserStatus:string
{
    use EnumOptions;

    case ACTIVE        ='active';
    case NOT_ACTIVE    ='not-active';
    case BLOCKED       ='blocked';

	public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
