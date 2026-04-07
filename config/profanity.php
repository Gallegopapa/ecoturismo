<?php

$jsonPath = env('PROFANITY_WORDS_JSON_PATH', base_path('config/profanity_words.json'));

$jsonWords = [];
if (is_string($jsonPath) && is_file($jsonPath)) {
    $content = @file_get_contents($jsonPath);
    $decoded = is_string($content) ? json_decode($content, true) : null;
    if (is_array($decoded)) {
        $jsonWords = array_values(array_filter($decoded, static fn ($word) => is_string($word) && trim($word) !== ''));
    }
}

return [
    // Optional external JSON file path. Keep this in .env if needed.
    'json_path' => $jsonPath,

    'words' => !empty($jsonWords) ? $jsonWords : [
        // English
        'fuck', 'fucks', 'fucked', 'fucking', 'shit', 'shitty', 'shits', 'bitch', 'bitches', 'bastard', 'asshole', 'assholes', 'dick', 'crap', 'damn',

        // Spanish - Solo palabras exactas y puntuales
        'puta', 'puto', 'putas', 'putos', 'hijo de puta', 'hdp',
        'mierda', 'mierdas', 'mierdero',
        'joder', 'jodida', 'jodido', 'jodiendo', 'jodete', 'jódete',
        'cabron', 'cabrón', 'cabrona',
        'gilipollas', 'gilipolla', 'gilipollez',
        'pendejo', 'pendeja', 'pendejos', 'pendejas',
        'coño', 'coños', 'coñazo', 'cojones', 'cojon', 'marica', 'marika', 'mk', 'guevon', 'huevon', 'malparido', 'malparida', 'malparidos', 'malparidas', 'pirobo', 'piroba', 'pirobas', 'pirobos',
        'zorra', 'zorro', 'zorras', 'zorros',
        'idiota', 'idiotas', 'imbecil', 'imbécil', 'imbeciles', 'tonto', 'tontos', 'tarado', 'tarados',

        // Otros insultos comunes
        'perra', 'culero', 'culera', 'golfa', 'putón', 'puton', 'maricon', 'maricón', 'maricona', 'maricones', 'mariconas',
        'chinga', 'chimba', 'chimbaa', 'chimbaaa', 'chimbita', 'chingada', 'chingar', 'chingao', 'chingado', 'chingados',
        'sidoso', 'sidosa'
    ],

    // If true, the matcher will attempt to match whole words only.
    'match_whole_words' => true,
];
