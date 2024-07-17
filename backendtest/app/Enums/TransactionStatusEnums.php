<?php
namespace App\Enums;

use App\Traits\EnumOptions;

enum TransactionStatusEnums:string
{
    use EnumOptions;

    case PENDING        ='pending';
    case APPROVED       ='approved';
    case REJECTED       ='rejected';

	public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
