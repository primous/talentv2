<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Durée de validité des jetons d'accès client (en jours)
    |--------------------------------------------------------------------------
    | Les clients sans compte reçoivent un lien magique sécurisé valide pendant
    | cette durée. Ils peuvent demander un nouveau lien à tout moment.
    */
    'client_token_lifetime_days' => (int) env('CLIENT_TOKEN_LIFETIME_DAYS', 14),
];
