<?php

namespace Tests;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Endroid\QrCode\QrCode;
use Generator\QrCodeGenerator;

class QrCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var QrCodeGenerator
     */
    private $qrCodeGenerator;

    public function setUp()
    {
        $this->urlGenerator = $this->getMockBuilder(UrlGenerator::class)
            ->disableOriginalConstructor()
            ->setMethods(array('generate'))
            ->getMock();

        $this->qrCodeGenerator = new QrCodeGenerator($this->urlGenerator);
    }

    public function testGenerate()
    {
        $text = "Dummy text";

        $qrCode = $this->qrCodeGenerator->generate($text);

        $this->assertInstanceOf(QrCode::class, $qrCode);
        $this->assertEquals($text, $qrCode->getText());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongTextType()
    {
        $text = array();

        $qrCode = $this->qrCodeGenerator->generate($text);
    }
}
