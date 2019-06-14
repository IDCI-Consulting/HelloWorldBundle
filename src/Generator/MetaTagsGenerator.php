<?php

namespace App\Generator;

/**
 * MetaTagsGenerator.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 *
 */
class MetaTagsGenerator
{
    /**
     * Generate
     *
     * @param string $title
     * @param string $url
     * @param string $type
     * @param string $imagePath
     *
     * @return array
     */
    public function generate(string $title, string $url, string $type, string $imagePath)
    {
        return [
            ['attributeName' => 'property', 'attributeValue' => 'og:title', 'content' => $title],
            ['attributeName' => 'property', 'attributeValue' => 'og:url',   'content' => $url],
            ['attributeName' => 'property', 'attributeValue' => 'og:type',  'content' => $type],
            ['attributeName' => 'property', 'attributeValue' => 'og:image', 'content' => $imagePath],
        ];
    }
}
