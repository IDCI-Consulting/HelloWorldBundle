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
     * Build open graph meta
     *
     * @param string $title
     * @param string $url
     * @param string $type
     * @param string $imagePath
     *
     * @return array
     */
    public function buildOpenGraphMeta($title, $url, $type, $imagePath)
    {
        return array(
            array('attributeName' => 'property', 'attributeValue' => 'og:title', 'content' => $title),
            array('attributeName' => 'property', 'attributeValue' => 'og:url',   'content' => $url),
            array('attributeName' => 'property', 'attributeValue' => 'og:type',  'content' => $type),
            array('attributeName' => 'property', 'attributeValue' => 'og:image', 'content' => $imagePath),
        );
    }
}