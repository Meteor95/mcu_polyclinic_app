<?php

namespace App\Helpers;
use nadar\quill\Lexer;
use Symfony\Component\DomCrawler\Crawler;

class QuillHelper {
    
    public static function quillToHtml($delta) {
        $lexer = new \nadar\quill\Lexer($delta);
        $html = $lexer->render();
        $crawler = new Crawler($html);
        $crawler->filter('*')->each(function (Crawler $node) {
            $node->getNode(0)->setAttribute('style', 'margin: 0px;');
        });
        return $crawler->html();
    }
}
