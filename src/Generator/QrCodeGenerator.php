<?php

namespace Generator;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Endroid\QrCode\Factory\QrCodeFactory;
use Endroid\QrCode\QrCode;
use QrCode\WriterRegistry;

/**
 * QrCodeGenerator.
 *
 * @author Brahim Boukoufallah <brahim.boukoufallah@idci-consulting.fr>
 */
class QrCodeGenerator
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var array
     */
    private $defaultOptions;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator The url generator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->defaultOptions = array(
            'writer' => 'png',
            'foreground_color' => array(
                'r' => 57,
                'g' => 74,
                'b' => 89,
                'a' => 0
            ),
        );
    }

    /**
     * Generate a qr code.
     *
     * @param string $text
     * @param string $writer
     * @param array  $options
     *
     * @return QrCode
     */
    public function generate($text, array $options = array())
    {
        if (!is_string($text)) {
            throw new \InvalidArgumentException('The text must a string.');
        }

        $writerRegistry = new WriterRegistry();
        $writerRegistry->loadDefaultWriters();

        $options = $this->mergeDefaultOptions($options);

        $qrCodeFactory = new QrCodeFactory($options, $writerRegistry);

        return $qrCodeFactory->create($text);
    }

    /**
     * Generate IDCI qr code.
     *
     * @param string $vcfFileName
     * @param string $locale
     * @param string $writer
     * @param array  $options
     *
     * @return QrCode
     */
    public function generateIDCI($vcfFileName, $locale, $writer, array $options = array())
    {
        $url = $this->urlGenerator->generate('cv', array(
            "_locale" => $locale,
            "theme"   => "idci",
            "name"    => $vcfFileName,
            "_format" => "lnk"
        ), UrlGeneratorInterface::ABSOLUTE_URL);

        $options = array_merge(array(
            'logo_path' => __DIR__.'/../../web/images/logo_idci_small.png',
            'logo_width' => 78,
        ));

        $options = $this->mergeDefaultOptions($options);

        return $this->generate($url, $writer, $options);
    }

    /**
     * Merge default options with given options.
     *
     * @param array $options
     *
     * @return arrau
     */
    private function mergeDefaultOptions(array $options = array())
    {
        return array_replace_recursive($this->defaultOptions, $options);
    }
}

