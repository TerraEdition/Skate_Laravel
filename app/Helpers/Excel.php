<?php

namespace App\Helpers;

use DOMDocument;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Excel
{
    public static function number_to_alphabet($number)
    {
        $number = intval($number);
        if ($number <= 0) {
            return '';
        }
        $alphabet = '';
        while ($number != 0) {
            $p = ($number - 1) % 26;
            $number = intval(($number - $p) / 26);
            $alphabet = chr(65 + $p) . $alphabet;
        }
        return $alphabet;
    }

    public static function alphabet_to_number($string)
    {
        $string = strtoupper($string);
        $length = strlen($string);
        $number = 0;
        $level = 1;
        while ($length >= $level) {
            $char = $string[$length - $level];
            $c = ord($char) - 64;
            $number += $c * (26 ** ($level - 1));
            $level++;
        }
        return $number;
    }
    public static function bold()
    {
        return array(
            'font' => [
                'bold' => true
            ],
        );
    }
    public static function fs($size)
    {
        return array(
            'font' => [
                'size' => $size
            ],
        );
    }
    public static function bg_hex($color)
    {
        return [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $color]
            ]
        ];
    }
    public static function color($color)
    {
        return [
            'font' => [
                'color' => ['rgb' => $color]
            ],
        ];
    }
    public static function underline()
    {
        return [
            'font' => [
                'underline' => true
            ],
        ];
    }
    public static function wrap_text()
    {
        return [
            'alignment' => [
                'wrapText' => true,
            ],
        ];
    }

    public static function center_middle()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
    }

    public static function center_top()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP
            ]
        ];
    }

    public static function right_top()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_TOP
            ]
        ];
    }

    public static function left_top()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_TOP
            ]
        ];
    }

    public static function border_l()
    {
        return [
            'borders' => [
                'left' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
    }

    public static function border_b()
    {
        return [
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
    }

    public static function border_r()
    {
        return [
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
    }

    public static function border_t()
    {
        return [
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
    }

    public static function border()
    {
        return [
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
                'right' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
                'left' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];
    }

    public static function applyRichTextWithHtml($htmlText)
    {
        if (empty($htmlText)) {
            return '';
        }
        $richText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
        $dom = new DOMDocument();
        $dom->loadHTML($htmlText);

        $tags = $dom->getElementsByTagName('body')->item(0)->childNodes;
        foreach ($tags as $tag) {
            $text = $tag->nodeValue;
            $bold = $tag->nodeName === 'b';
            $italic = $tag->nodeName === 'i';
            $underline = $tag->nodeName === 'u';

            $part = $richText->createTextRun($text);

            if ($bold) {
                $part->getFont()->setBold(true);
            }
            if ($italic) {
                $part->getFont()->setItalic(true);
            }
            if ($underline) {
                $part->getFont()->setUnderline(true);
            }
        }

        return $richText;
    }
}
