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
        if (null === $key) {
            return $this->formData;
        } elseif (isset($this->formData[$key]) && null !== $this->formData[$key]) {
            return $this->formData[$key];
        }

        return $default;
    }
}