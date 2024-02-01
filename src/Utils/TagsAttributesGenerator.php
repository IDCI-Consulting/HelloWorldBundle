<?php

namespace App\Utils;

class TagsAttributesGenerator
{
    public static function generate(array $tags): array
    {
        $tagsWithAttributes = [];

        foreach ($tags as $tagName) {
            $tagsWithAttributes[] = [
                'name' => $tagName,
                'color' => self::guessValue(["black", "blue", "green"]),
                'size' => self::guessValue(["small", "medium", "big"]),
            ];
        }

        return $tagsWithAttributes;
    }

    public static function guessValue(array $availableValues): string
    {
        return $availableValues[array_rand($availableValues, 1)];
    }
}