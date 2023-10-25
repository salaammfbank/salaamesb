<?php

namespace Noorfarooqy\SalaamEsb\Contracts;

enum ModulesEnum
{
    case dashboard;
    case crb;
    case kyc;
    case iprs;
    case promotions;
    case products;
    case departments;
    case tickets;
    case users;
    case roles;
    case permissions;
    case settings;
    case incidents;
    case documentations;
    case currency;
    case knowledgebase;
    case notifications;
    case ebanking;
    case accounts;
    case branches;
    case i365;

    public static function toJson()
    {
        $cases = static::cases();
        $data = [];
        foreach ($cases as $key => $case) {
            $data[] = (object) ['name' => $case->name, 'value' => $case->value ?? strtoupper($case->name)];
        }

        return $data;
    }

    public static function toString()
    {
        $cases = static::cases();
        $data = '';
        foreach ($cases as $key => $case) {
            $data = strlen($data) <= 0 ? $case->name : $data . ',' . $case->name;
        }

        return $data;
    }
}
