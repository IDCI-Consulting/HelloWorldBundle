<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ContactEvent extends Event
{
    protected $formData = [];

    public function __construct(array $formData)
    {
        $this->formData = $formData;
    }

    public function getFormData(string $key = null, string $default = null)
    {
        if ($key === null) {
            return $this->formData;
        } else if (isset($this->formData[$key]) && $this->formData[$key] !== null) {
            return $this->formData[$key];
        }

        return $default;
    }
}