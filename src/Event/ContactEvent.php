<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ContactEvent extends Event
{
    protected $formData = [];

    public function __construct(Array $formData)
    {
        $this->formData = $formData;
    }

    public function getFormData(): Array
    {
        return $this->formData;
    }

    public function getFormFieldData(String $field)
    {
        if(isset($this->formData[$field])) {
            return $this->formData[$field];
        }

        return null;
    }
}