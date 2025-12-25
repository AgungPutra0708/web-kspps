<?php

namespace App\Services;

use App\Models\ProfileKoperasiModel;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;

class QrCodeService
{
    /**
     * Generate QR Code SVG
     * 
     * @param string $url
     * @return string SVG
     */
    public function generate(string $url): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(
                400,    // size
                8       // margin (quiet zone)
            ),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $svg = $writer->writeString($url);

        return $this->injectLogo($svg);
    }

    /**
     * Inject logo to center of SVG QR
     */
    private function injectLogo(string $svg): string
    {
        $dataProfile = ProfileKoperasiModel::first();

        $logoPath = public_path('storage/' . $dataProfile->logo_koperasi_indonesia);

        if (!file_exists($logoPath)) {
            return $svg;
        }

        $logoBase64 = base64_encode(
            file_get_contents($logoPath)
        );

        $logoSvg = <<<SVG
            <image
                x="38%"
                y="38%"
                width="24%"
                height="24%"
                href="data:image/png;base64,{$logoBase64}"
                preserveAspectRatio="xMidYMid meet"
            />
            SVG;

        // sisipkan logo sebelum penutup svg
        return str_replace('</svg>', $logoSvg . '</svg>', $svg);
    }
}
