<?php

declare(strict_types=1);

namespace Baka\Validations;

use DateTime;

class Date
{
    /**
     * Is validate date?
     *
     * @param string|null $date
     * @param string $format
     *
     * @return bool
     */
    public static function isValid(?string $date, string $format = 'Y-m-d'): bool
    {
        if ($date === null || empty($date)) {
            return false;
        }

        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
