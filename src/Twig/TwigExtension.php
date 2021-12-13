<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class TwigExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'svgIcon'], ['is_safe' => ['html']]),
            new TwigFunction('extension', [$this, 'getExtension'], ['is_safe' => ['html']]),
        ];
    }


    /**
     * Génère le code HTML pour une icone SVG
     * @param string $name
     * @return string
     */
    public function svgIcon(string $name): string
    {
        return <<<HTML
        <svg class="icon icon-{$name}">
          <use xlink:href="/sprite.svg#{$name}"></use>
        </svg>
HTML;
    }

    public function getExtension(string  $name): String {
        $var = explode( '.' ,$name);
        if(count($var)> 0) {
            return end($var);
        }
        return  'pdf';
    }

}