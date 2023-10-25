<?php

namespace Noorfarooqy\SalaamEsb\Traits;

trait HelperTrait
{

    public function maskReference($reference, $len = 8, $replacement = "*")
    {
        $replacement = str_repeat($replacement, $len);
        $viewable = substr($reference, $len, strlen($reference));
        return $replacement . $viewable;
    }

    public function moneyFormat($amount)
    {
        return number_format(((float) $amount), 2);
    }
}

