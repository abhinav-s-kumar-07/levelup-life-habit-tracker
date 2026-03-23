<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class PixelAssetController extends Controller
{
    public function avatar(string $filename): Response
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);

        if (!preg_match('/^avatar([1-9]|10)$/', $name, $m)) {
            abort(404);
        }

        $idx = (int) $m[1];
        $sprites = $this->avatarSprites();
        $sprite = $sprites[$idx] ?? $sprites[1];

        $svg = $this->svgFromPixels(
            $sprite['rows'],
            $sprite['map'],
            $sprite['bg']
        );

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function frame(string $filename): Response
    {
        $name = strtolower(pathinfo($filename, PATHINFO_FILENAME));
        $svg = match ($name) {
            'frame_bronze' => $this->frameBronze(),
            'frame_silver' => $this->frameSilver(),
            'frame_gold' => $this->frameGold(),
            'frame_diamond' => $this->frameDiamond(),
            default => $this->frameSpecial(),
        };

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function svgFromPixels(array $rows, array $map, string $background): string
    {
        $cell = 10;
        $w = strlen($rows[0]) * $cell;
        $h = count($rows) * $cell;
        $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='{$w}' height='{$h}' viewBox='0 0 {$w} {$h}' shape-rendering='crispEdges'>";
        $svg .= "<rect width='100%' height='100%' fill='{$background}'/>";

        foreach ($rows as $y => $row) {
            for ($x = 0; $x < strlen($row); $x++) {
                $key = $row[$x];
                $color = $map[$key] ?? null;
                if (!$color) continue;
                $rx = $x * $cell;
                $ry = $y * $cell;
                $svg .= "<rect x='{$rx}' y='{$ry}' width='{$cell}' height='{$cell}' fill='{$color}'/>";
            }
        }

        $svg .= "</svg>";
        return $svg;
    }

    private function frameSvgBase(string $outer, string $mid, string $inner): string
    {
        return "<svg xmlns='http://www.w3.org/2000/svg' width='110' height='110' viewBox='0 0 110 110' shape-rendering='crispEdges'>"
            . "<rect width='110' height='110' fill='transparent'/>"
            . "<rect x='0' y='0' width='110' height='110' fill='{$outer}'/>"
            . "<rect x='6' y='6' width='98' height='98' fill='{$mid}'/>"
            . "<rect x='11' y='11' width='88' height='88' fill='transparent'/>"
            . "<rect x='11' y='11' width='88' height='4' fill='{$inner}'/>"
            . "<rect x='11' y='95' width='88' height='4' fill='{$inner}'/>"
            . "<rect x='11' y='11' width='4' height='88' fill='{$inner}'/>"
            . "<rect x='95' y='11' width='4' height='88' fill='{$inner}'/>"
            . "</svg>";
    }

    private function frameBronze(): string
    {
        $svg = $this->frameSvgBase('#CD7F32', '#E6A15A', '#8E5A20');
        return str_replace('</svg>',
            "<rect x='0' y='0' width='14' height='14' fill='#8E5A20'/>"
            . "<rect x='96' y='0' width='14' height='14' fill='#8E5A20'/>"
            . "<rect x='0' y='96' width='14' height='14' fill='#8E5A20'/>"
            . "<rect x='96' y='96' width='14' height='14' fill='#8E5A20'/>"
            . "</svg>", $svg);
    }

    private function frameSilver(): string
    {
        $svg = $this->frameSvgBase('#B0B7C3', '#D4D9E1', '#7B8797');
        return str_replace('</svg>',
            "<rect x='18' y='18' width='74' height='2' fill='#7B8797'/>"
            . "<rect x='18' y='90' width='74' height='2' fill='#7B8797'/>"
            . "<rect x='18' y='18' width='2' height='74' fill='#7B8797'/>"
            . "<rect x='90' y='18' width='2' height='74' fill='#7B8797'/>"
            . "</svg>", $svg);
    }

    private function frameGold(): string
    {
        $svg = $this->frameSvgBase('#D8AD2F', '#F6D565', '#9C7A14');
        return str_replace('</svg>',
            "<polygon points='40,10 47,2 55,10 63,2 70,10' fill='#9C7A14'/>"
            . "<rect x='40' y='10' width='30' height='4' fill='#9C7A14'/>"
            . "</svg>", $svg);
    }

    private function frameDiamond(): string
    {
        $svg = $this->frameSvgBase('#57CCFF', '#A9E8FF', '#1E88B8');
        return str_replace('</svg>',
            "<polygon points='55,2 62,10 55,18 48,10' fill='#1E88B8'/>"
            . "<polygon points='108,55 100,62 92,55 100,48' fill='#1E88B8'/>"
            . "<polygon points='55,108 62,100 55,92 48,100' fill='#1E88B8'/>"
            . "<polygon points='2,55 10,62 18,55 10,48' fill='#1E88B8'/>"
            . "</svg>", $svg);
    }

    private function frameSpecial(): string
    {
        return "<svg xmlns='http://www.w3.org/2000/svg' width='110' height='110' viewBox='0 0 110 110' shape-rendering='crispEdges'>"
            . "<rect width='110' height='110' fill='transparent'/>"
            . "<rect x='0' y='0' width='110' height='110' fill='#8EA8FF'/>"
            . "<rect x='6' y='6' width='98' height='98' fill='#EAD9FF'/>"
            . "<rect x='11' y='11' width='88' height='88' fill='transparent'/>"
            . "<rect x='11' y='11' width='88' height='4' fill='#6B46C1'/>"
            . "<rect x='11' y='95' width='88' height='4' fill='#6B46C1'/>"
            . "<rect x='11' y='11' width='4' height='88' fill='#6B46C1'/>"
            . "<rect x='95' y='11' width='4' height='88' fill='#6B46C1'/>"
            . "<circle cx='55' cy='55' r='6' fill='#6B46C1'/>"
            . "</svg>";
    }

    private function avatarSprites(): array
    {
        return [
            1 => [
                'bg' => '#ECF7FF',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123333200',
                    '00123333200',
                    '00024442000',
                    '00025552000',
                    '00066666000',
                    '00076667000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#9C2A2A', '2' => '#C63C3C', '3' => '#F2C7A0', '4' => '#19233D',
                    '5' => '#2E78D9', '6' => '#1F5CB8', '7' => '#24324D',
                ],
            ],
            2 => [
                'bg' => '#F3EEFF',
                'rows' => [
                    '00000100000',
                    '00001110000',
                    '00012221000',
                    '00123332100',
                    '00123332100',
                    '00024442000',
                    '00025552000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#7045D8', '2' => '#8B61F2', '3' => '#F1C9AA', '4' => '#1A2238',
                    '5' => '#7B4DE0', '6' => '#6139B8', '7' => '#2B334E',
                ],
            ],
            3 => [
                'bg' => '#EEFFF6',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123333200',
                    '00123333200',
                    '00024442000',
                    '00025552000',
                    '00025652000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#1E7C5F', '2' => '#2AAE87', '3' => '#E7BA96', '4' => '#253149',
                    '5' => '#2E9C68', '6' => '#237D53', '7' => '#2A334B',
                ],
            ],
            4 => [
                'bg' => '#FFF3EC',
                'rows' => [
                    '00000100000',
                    '00001110000',
                    '00012221000',
                    '00123333200',
                    '00123333200',
                    '00024442000',
                    '00025552000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#A35E2B', '2' => '#C3773C', '3' => '#F0C9A8', '4' => '#2A354E',
                    '5' => '#D98B3A', '6' => '#B06B2E', '7' => '#2B344A',
                ],
            ],
            5 => [
                'bg' => '#F0F5FF',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123333200',
                    '00123833200',
                    '00024442000',
                    '00025552000',
                    '00026662000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#A6ADB6', '2' => '#C8CED6', '3' => '#F0C9AD', '4' => '#2A3650',
                    '5' => '#8096C7', '6' => '#6279A8', '7' => '#2B344B', '8' => '#E11D48',
                ],
            ],
            6 => [
                'bg' => '#FFF0FA',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123333200',
                    '00123333200',
                    '00024442000',
                    '00025552000',
                    '00025552000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#D1398A', '2' => '#FF5CAD', '3' => '#F3C9AD', '4' => '#2A3750',
                    '5' => '#4E7ED9', '6' => '#355FAF', '7' => '#2B344A',
                ],
            ],
            7 => [
                'bg' => '#EFFBFF',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123993200',
                    '00123333200',
                    '00024442000',
                    '00025552000',
                    '00026662000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#3C4A66', '2' => '#5A6C8F', '3' => '#E8BE9E', '4' => '#1D2A42',
                    '5' => '#45B1D8', '6' => '#2F8AA8', '7' => '#2C3448', '9' => '#F9FAFB',
                ],
            ],
            8 => [
                'bg' => '#F4FFF1',
                'rows' => [
                    '00001110000',
                    '00012221000',
                    '00123333200',
                    '00123333200',
                    '00024442000',
                    '00025452000',
                    '00026662000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#4A8F2A', '2' => '#64B83C', '3' => '#EAC39F', '4' => '#25334A',
                    '5' => '#91B740', '6' => '#739133', '7' => '#2A344A',
                ],
            ],
            9 => [
                'bg' => '#FFF4F1',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123333200',
                    '00123833200',
                    '00024442000',
                    '00025552000',
                    '00026662000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#A23F2F', '2' => '#D15540', '3' => '#F0C19D', '4' => '#263349',
                    '5' => '#B6496C', '6' => '#923957', '7' => '#2A3449', '8' => '#B91C1C',
                ],
            ],
            10 => [
                'bg' => '#F0F7FF',
                'rows' => [
                    '00011111000',
                    '00112222100',
                    '00123333200',
                    '00123333200',
                    '00024442000',
                    '00025552000',
                    '00026662000',
                    '00026662000',
                    '00070007000',
                    '00070007000',
                    '00000000000',
                ],
                'map' => [
                    '0' => null, '1' => '#0E7B8E', '2' => '#14A7C2', '3' => '#E8BEA0', '4' => '#22334D',
                    '5' => '#4F8CE0', '6' => '#3A6FC2', '7' => '#28364E',
                ],
            ],
        ];
    }
}
