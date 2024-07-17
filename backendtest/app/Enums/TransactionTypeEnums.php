<?php
namespace App\Enums;

use App\Traits\EnumOptions;

enum TransactionTypeEnums:string
{
    use EnumOptions;

    case CREDIT         ='credit';
    case DEBIT          ='debit';

	public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
