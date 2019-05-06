<?php

namespace App\QrCode;

use Endroid\QrCode\Writer\BinaryWriter;
use Endroid\QrCode\Writer\DebugWriter;
use Endroid\QrCode\Writer\EpsWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\WriterRegistry as BaseRegistry;

class WriterRegistry extends BaseRegistry
{
    public function loadDefaultWriters()
    {
        if (count($this->writers) > 0) {
            return;
        }

        $this->addWriter(new BinaryWriter());
        $this->addWriter(new DebugWriter());
        $this->addWriter(new EpsWriter());
        $this->addWriter(new PngWriter(), true);
        $this->addWriter(new SvgWriter());
    }
}
