<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\File;

class NoProfanity implements Rule
{
    protected $words = [];
    protected $matchWhole = true;

    public function __construct()
    {
        $jsonPath = config('profanity.json_path');
        $jsonWords = [];

        if (is_string($jsonPath) && $jsonPath !== '' && File::exists($jsonPath)) {
            $decoded = json_decode((string) File::get($jsonPath), true);
            if (is_array($decoded)) {
                $jsonWords = array_values(array_filter($decoded, static fn ($word) => is_string($word) && trim($word) !== ''));
            }
        }

        $config = config('profanity.words', []);
        $configWords = is_array($config) ? $config : [];

        // Prefer JSON words when available so updates to JSON work without touching PHP config.
        $this->words = !empty($jsonWords) ? $jsonWords : $configWords;
        $this->matchWhole = config('profanity.match_whole_words', true);
    }

    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true;
        }
        // Normalizar texto y palabras para cubrir acentos, leetspeak,
        // separadores y repeticiones exageradas de letras.
        $normalize = function ($s) {
            $s = mb_strtolower($s);
            $map = [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
                'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
                'ñ' => 'n', 'ç' => 'c',
                '0' => 'o', '1' => 'i', '3' => 'e', '4' => 'a', '5' => 's', '7' => 't',
                '@' => 'a', '$' => 's'
            ];
            $s = strtr($s, $map);
            // Reemplazar cualquier caracter que no sea letra o número por espacio
            $s = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $s);
            // Colapsar espacios
            $s = preg_replace('/\s+/u', ' ', $s);
            // Colapsar repeticiones exageradas (ej: chiiimba -> chimba)
            $s = preg_replace('/([\p{L}])\1{1,}/u', '$1', $s);
            return trim($s);
        };

        $text = $normalize($value);

        if (empty($this->words)) {
            return true;
        }

        foreach ($this->words as $word) {
            $normalizedWord = $normalize($word);
            if ($normalizedWord === '') {
                continue;
            }

            $tokens = preg_split('/\s+/u', $normalizedWord, -1, PREG_SPLIT_NO_EMPTY);
            if (empty($tokens)) {
                continue;
            }

            $tokenPatterns = [];
            foreach ($tokens as $token) {
                $chars = preg_split('//u', $token, -1, PREG_SPLIT_NO_EMPTY);
                if (empty($chars)) {
                    continue;
                }

                $escapedChars = array_map(static fn ($char) => preg_quote($char, '/'), $chars);
                // Permite separadores y letras repetidas para capturar ofuscaciones.
                $tokenPatterns[] = implode('\s*', array_map(static fn ($char) => $char . '+', $escapedChars));
            }

            if (empty($tokenPatterns)) {
                continue;
            }

            $wordPattern = implode('\s+', $tokenPatterns);
            $regex = $this->matchWhole
                ? '/(?<![\p{L}\p{N}])' . $wordPattern . '(?![\p{L}\p{N}])/u'
                : '/' . $wordPattern . '/u';

            if (preg_match($regex, $text)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'El comentario contiene palabras inapropiadas.';
    }
}
