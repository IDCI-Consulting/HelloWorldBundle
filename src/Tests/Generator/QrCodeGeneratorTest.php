<?php

namespace Tests;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $this->urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(array('generate', 'setContext', 'getContext'))
            ->getMock();

        $this->qrCodeGenerator = new QrCodeGenerator($this->urlGenerator);
    }

    public function testGenerate()
    {
        $text = "Dummy text";

        $qrCode = $this->qrCodeGenerator->generate($text, array('writer' => 'svg'));

        $this->assertInstanceOf(QrCode::class, $qrCode);
        $this->assertEquals($text, $qrCode->getText());
        $this->assertEquals('image/svg+xml', $qrCode->getContentType());
    }

    public function testGenerateIDCI()
    {
        $vcfFileName = "dummy_name";
        $locale = 'fr';
        $url = "http://dummy.com";


        $this->urlGenerator
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('cv'),
                $this->equalTo(array(
                    "_format" => "lnk",
                    "_locale" => $locale,
                    "name"    => $vcfFileName,
                    "theme"   => "idci",
                )),
                $this->equalTo(UrlGeneratorInterface::ABSOLUTE_URL)
            )
            ->will($this->returnValue($url))
        ;

        $qrCode = $this->qrCodeGenerator->generateIDCI($vcfFileName, $locale);

        $this->assertInstanceOf(QrCode::class, $qrCode);
        $this->assertEquals($url, $qrCode->getText());
        $this->assertEquals('image/png', $qrCode->getContentType());
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
