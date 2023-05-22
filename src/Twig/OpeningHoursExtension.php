<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class OpeningHoursExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('openingHours', [$this, 'isOpeningHours']),
        ];
    }

    public function isOpeningHours($openingHours)
    {
        return $openingHours->isClosed();
    }
}
