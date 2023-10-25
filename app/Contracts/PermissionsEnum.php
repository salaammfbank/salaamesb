<?php
namespace Noorfarooqy\SalaamEsb\Contracts;
enum PermissionsEnum
{
    case read;
    case create;
    case update;
    case activate;
    case deactivate;
    case delete;

    public static function toJson()
    {
        $cases = static::cases();
        $data = [];
        foreach ($cases as $key => $case) {
            $data[] = (object) ['name' => $case->name, 'value' => $case->value ?? strtoupper($case->name) ];
        }

        return $data;
    }

    public static function toString()
    {
        $cases = static::cases();
        $data = '';
        foreach ($cases as $key => $case) {
            $data = strlen($data) <= 0 ? $case->name : $data.','.$case->name;
        }

        return $data;
    }
}
