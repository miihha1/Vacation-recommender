<?php

namespace App\Services;

use App\Models\Destination;

class RecommendationService
{
    public function text(Destination $destination, object $climate): string
    {
        $avg = round(($climate->avg_min + $climate->avg_max) / 2);
        $tone = $climate->avg_max >= 28
            ? 's výrazne letným charakterom počasia'
            : 's miernym a pohodlným počasím na výlety';

        return sprintf(
            '%s sa v tomto mesiaci hodí najmä na %s. Priemerná denná teplota okolo %d °C vytvára profil destinácie %s. Odporúčanie vzniká automaticky z uložených klimatických dát, typu destinácie a dostupnosti letu z Viedne.',
            $destination->name,
            implode(', ', VacationCatalog::typeLabels($destination->types)),
            $avg,
            $tone
        );
    }
}
