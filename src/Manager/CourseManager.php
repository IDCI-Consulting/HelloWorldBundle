<?php

namespace App\Manager;

class CourseManager
{
    private $regex;

    public function __construct()
    {
        $this->regex = "/".
            "(##(?<title>.*))??".
            "(\[description\](?<description>.*))??".
            "\{(?<day>.*)\}".
            "(?<content>[?.\n\wéàèçâô#=<>()\/ *';&\\\"'\-,:!]*?)/siU"
        ;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    public function matchContent($content)
    {
        preg_match_all(
            $this->regex,
            $content,
            $matches
        );

        return $matches;
    }
}
