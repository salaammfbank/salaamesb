<?php

namespace App\Enums;

enum ErrorEnums: string
{
    case ESB_001 = 'Default Error description';
    case ESB_002 = 'SMS - Invalid format number ';

    public static function getErrorDescription($error, $other_errors = [])
    {
        if (!$error) {
            return '';
        }

        $cases = count($other_errors) > 0 ? array_merge($other_errors, static::cases()) : static::cases();
        foreach ($cases as $key => $case) {
            if ($case->name == $error) {
                return $case->value;
            }
        }

        return 'Uknown error description';
    }
}
