<?php

namespace App\Services;

class VacationCatalog
{
    public static function months(): array
    {
        return [
            1 => 'Január', 2 => 'Február', 3 => 'Marec', 4 => 'Apríl',
            5 => 'Máj', 6 => 'Jún', 7 => 'Júl', 8 => 'August',
            9 => 'September', 10 => 'Október', 11 => 'November', 12 => 'December',
        ];
    }

    public static function types(): array
    {
        return [
            'beach' => 'more a pláž',
            'nature' => 'hory a príroda',
            'history' => 'historické mestá',
            'city' => 'mestský výlet',
            'activity' => 'aktivity a dobrodružstvo',
        ];
    }

    public static function temperatures(): array
    {
        return [
            'hot' => 'horúco (30 °C+)',
            'warm' => 'teplo (20-29 °C)',
            'mild' => 'príjemne (10-19 °C)',
            'any' => 'jedno mi to',
        ];
    }

    public static function distances(): array
    {
        return [
            'short' => 'do 3 hodín letu',
            'medium' => 'do 5 hodín letu',
            'any' => 'kdekoľvek',
        ];
    }

    public static function typeLabels(array|string $types): array
    {
        $items = is_array($types) ? $types : explode(',', $types);

        return array_map(fn (string $type) => self::types()[$type] ?? $type, array_filter($items));
    }
}
