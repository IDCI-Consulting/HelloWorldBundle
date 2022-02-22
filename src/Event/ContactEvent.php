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

    public function getFormData(string $field = "default")
    {
        if ($field === "default") {
            return $this->formData;
        }
        else if (isset($this->formData[$field])) {
            return $this->formData[$field];
        }

        return null;
    }
}