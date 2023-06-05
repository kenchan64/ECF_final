<?php

namespace App\Twig;

use App\Entity\OpeningHours;
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

    public function isOpeningHours(OpeningHours $openingHours): bool
    {
        return $openingHours->isClosed();
    }
}
