<?php

namespace Anax\TextFilter;

/**
 * Shortcode to format when working with text.
 *
 */
trait TShortcode
{
    /**
     * Shortcode to quicker format text as HTML.
     *
     * @param string $text text to be converted.
     *
     * @return string the formatted text.
     */
    public function shortCode($text)
    {
        /* Needs PHP 7
        $patternsAndCallbacks = [
            "/\[(FIGURE)[\s+](.+)\]/" => function ($match) {
                return self::ShortCodeFigure($matches[2]);
            },
            "/(```([\w]*))\n([^`]*)```[\n]{1}/s" => function ($match) {
                return $this->syntaxHighlightGeSHi($matches[3], $matches[2]);
            },
        ];

        return preg_replace_callback_array($patternsAndCallbacks, $text);
        */

        $patterns = [
            "/\[(FIGURE)[\s+](.+)\]/",
            //'/\[(YOUTUBE) src=(.+) width=(.+) caption=(.+)\]/',
            "/\[(YOUTUBE)[\s+](.+)\]/",
            "/\[(CODEPEN)[\s+](.+)\]/",
            "/\[(ASCIINEMA)[\s+](.+)\]/",
            "/\[(BOOK)[\s+](.+)\]/",
            //"/(```)([\w]*)\n([.]*)```[\n]{1}/s",
            "/(```)([\w]*)\n(.*?)```\n/s",
            '/\[(INFO)\]/',
            '/\[(\/INFO)\]/',
            '/\[(WARNING)\]/',
            '/\[(\/WARNING)\]/',
        ];

        return preg_replace_callback(
            $patterns,
            function ($matches) {
                switch ($matches[1]) {
                    case "FIGURE":
                        return self::shortCodeFigure($matches[2]);
                    break;

                    case "YOUTUBE":
                        return self::shortCodeYoutube($matches[2]);
                    break;

                    case "CODEPEN":
                        return self::shortCodeCodepen($matches[2]);
                    break;

                    case "ASCIINEMA":
                        return self::shortCodeAsciinema($matches[2]);
                    break;

                    case "BOOK":
                        return self::shortCodeBook($matches[2]);
                    break;

                    case "```":
                        //return $this->syntaxHighlightGeSHi($matches[3], $matches[2]);
                        return $this->syntaxHighlightJs($matches[3], $matches[2]);
                    break;

                    case 'INFO':
                        return <<<EOD
<div class="info">
    <span class="icon fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-info fa-stack-1x fa-inverse" aria-hidden="true"></i>
    </span>
    <div markdown=1>
EOD;
                        break;

                    case 'WARNING':
                        return <<<EOD
<div class="warning">
    <span class="icon fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-exclamation-triangle fa-stack-1x fa-inverse" aria-hidden="true"></i>
    </span>
    <div markdown=1>
EOD;
                        break;

                    case '/INFO':
                    case '/WARNING':
                        return "</div></div>";
                        break;

                    default:
                        return "{$matches[1]} is unknown shortcode.";
                }
            },
            $text
        );
    }



    /**
     * Init shortcode handling by preparing the option list to an array,
     * for those using arguments.
     *
     * @param string $options for the shortcode.
     *
     * @return array with all the options.
     */
    public static function shortCodeInit($options)
    {
        preg_match_all('/[a-zA-Z0-9]+="[^"]+"|\S+/', $options, $matches);

        $res = array();
        foreach ($matches[0] as $match) {
            $pos = strpos($match, '=');
            if ($pos === false) {
                $res[$match] = true;
            } else {
                $key = substr($match, 0, $pos);
                $val = trim(substr($match, $pos+1), '"');
                $res[$key] = $val;
            }
        }

        return $res;
    }



    /**
     * Shortcode for [YOUTUBE].
     *
     * Usage example: [YOUTUBE src=id-for-the-tube width=630 caption=""]
     *
     * @param string $options for the shortcode.
     *
     * @return array with all the options.
     */
    public static function shortCodeYoutube($options)
    {
        $options= array_merge(
            [
                "id"    => null,
                "class" => null,
                "src" => null,
                "list" => null,
                "time" => null,
                "width" => 600,
                "ratio" => 16/9,
                "caption" => null,
            ],
            self::ShortCodeInit($options)
        );
        extract($options, EXTR_SKIP);

        $id = $id ? " id=\"$id\"" : null;
        $class = $class ? " class=\"figure $class\"" : " class=\"figure\"";
        $list = $list ? "?listType=playlist&amp;list=$list" : null;
        $time = $time ? "#t=$time" : null;
        $height = ceil($width / $ratio);

        //$caption = t("Figure: !CAPTION", ["!CAPTION" => $caption]);
        if ($caption) {
            $caption = "<figcaption markdown=1>{$caption}</figcaption>";
        }

        // @codingStandardsIgnoreStart
        $html = <<<EOD
<figure{$id}{$class}>
<iframe width="$width" height="$height" src="https://www.youtube.com/embed/{$src}{$list}{$time}" frameborder="0" allowfullscreen></iframe>
{$caption}
</figure>
EOD;
        // @codingStandardsIgnoreEnd

        return $html;
    }



    /**
     * Shortcode for [CODEPEN].
     *
     * Usage example: [CODEPEN src=id-for-the-tube width=630 caption=""]
     *
     * @param string $options for the shortcode.
     *
     * @return array with all the options.
     */
    public static function shortCodeCodepen($options)
    {
        $options= array_merge(
            [
                "id"    => null,
                "class" => null,
                "src" => null,
                "user" => null,
                "title" => null,
                "tab" => "result",
                "theme" => 0,
                "height" => 300,
                "width" => "100%",
                "caption" => null,
            ],
            self::ShortCodeInit($options)
        );
        extract($options, EXTR_SKIP);

        $id = $id ? " id=\"$id\"" : null;
        $class = $class ? " class=\"figure figure-codepen $class\"" : " class=\"figure figure-codepen\"";

        //$caption = t("Figure: !CAPTION", ["!CAPTION" => $caption]);
        if ($caption) {
            $caption = "<figcaption markdown=1>{$caption}</figcaption>";
        }

        // @codingStandardsIgnoreStart
        $html = <<<EOD
<figure{$id}{$class} width="$width">
<p data-height="$height" data-theme-id="$theme" data-slug-hash="$src" data-default-tab="$tab" data-user="$user" data-embed-version="2" data-pen-title="$title" class="codepen">See the <a href="https://codepen.io/$user/pen/$src/">Pen</a> on <a href="https://codepen.io">CodePen</a>.</p>
{$caption}
</figure>
<script async src="https://static.codepen.io/assets/embed/ei.js"></script>
EOD;
        // @codingStandardsIgnoreEnd

        return $html;
    }



    /**
     * Shortcode for <figure>.
     *
     * Usage example: [FIGURE src="img/home/me.jpg" caption="Me" alt="Bild på mig" nolink="nolink"]
     *
     * @param string $options for the shortcode.
     *
     * @return array with all the options.
     */
    public static function shortCodeFigure($options)
    {
        // Merge incoming options with default and expose as variables
        $options= array_merge(
            [
                "id"    => null,
                "class" => null,
                "src"   => null,
                "title" => null,
                "alt"   => null,
                "caption" => null,
                "href"  => null,
                "nolink" => false,
            ],
            self::ShortCodeInit($options)
        );
        extract($options, EXTR_SKIP);

        $id = $id ? " id=\"$id\"" : null;
        $class = $class ? " class=\"figure $class\"" : " class=\"figure\"";
        $title = $title ? " title=\"$title\"" : null;

        if (!$alt && $caption) {
            $alt = $caption;
        }

        if (!$href) {
            $pos = strpos($src, "?");
            $href = $pos ? substr($src, 0, $pos) : $src;
        }

        $start = null;
        $end = null;
        if (!$nolink) {
            $start = "<a href=\"{$href}\">";
            $end = "</a>";
        }

        if ($caption) {
            $caption = "<figcaption markdown=1>{$caption}</figcaption>";
        }

        $html = <<<EOD
<figure{$id}{$class}>
{$start}<img src="{$src}" alt="{$alt}"{$title}/>{$end}
{$caption}
</figure>
EOD;

        return $html;
    }



    /**
     * Shortcode for [asciinema].
     *
     * @param string $code the code to process.
     * @param string $options for the shortcode.
     * @return array with all the options.
     */
    public static function shortCodeAsciinema($options)
    {
        // Merge incoming options with default and expose as variables
        $options= array_merge(
            [
                "id" => null,
                "class" => null,
                "src" => null,
                "title" => null,
                "caption" => null,
            ],
            self::ShortCodeInit($options)
        );
        extract($options, EXTR_SKIP);

        $id = $id ? " id=\"$id\"" : null;
        $class = $class ? " class=\"figure asciinema $class\"" : " class=\"figure asciinema\"";
        $title = $title ? " title=\"$title\"" : null;

        $html = <<<EOD
<figure{$id}{$class}$title>
<script type="text/javascript" src="https://asciinema.org/a/{$src}.js" id="asciicast-{$src}" async></script>
<figcaption markdown=1>{$caption}</figcaption>
</figure>
EOD;

        return $html;
    }



    /**
     * Shortcode for [book].
     *
     * @param string $code the code to process.
     * @param string $options for the shortcode.
     * @return array with all the options.
     */
    public static function shortCodeBook($options)
    {
        // Merge incoming options with default and expose as variables
        $options= array_merge(
            [
                "isbn" => null,
            ],
            self::ShortCodeInit($options)
        );
        extract($options, EXTR_SKIP);

        $stores = [
            "BTH" => "http://bth.summon.serialssolutions.com/?#!/search?ho=t&amp;q={$isbn}",
            "Libris" => "http://libris.kb.se/hitlist?q={$isbn}",
            "Google Books" => "http://books.google.com/books?q={$isbn}",
            "Bokus" => "http://www.bokus.com/bok/{$isbn}",
            "Adlibris" => "http://www.adlibris.com/se/product.aspx?isbn={$isbn}",
            "Amazon" => "http://www.amazon.com/s/ref=nb_ss?url=field-keywords={$isbn}",
            "Barnes&Noble" => "http://search.barnesandnoble.com/booksearch/ISBNInquiry.asp?r=1&IF=N&EAN={$isbn}",
        ];

        $html = null;
        foreach ($stores as $key => $val) {
            $html .= "<a href='$val'>$key</a> &bull; ";
        }
        return substr($html, 0, -8);
    }



    /**
     * Shortcode for including a SVG-image inside a <figure>.
     *
     * @param string $code the code to process.
     * @param string $options for the shortcode.
     * @return array with all the options.
     */
/*    public static function ShortCodeSVGFigure($options) {
        // Merge incoming options with default and expose as variables
        $options= array_merge(
            [
                "id"    => null,
                "class" => null,
                "src"   => null,
                "title" => null,
                "alt"   => null,
                "caption" => null,
                "href"  => null,
                "nolink" => false,
                //'path' => null,
            ],
            self::ShortCodeInit($options)
        );
        extract($options, EXTR_SKIP);

        $id = $id ? " id=\"$id\"" : null;
        $class = $class ? " class=\"figure $class\"" : " class=\"figure\"";
        $title = $title ? " title=\"$title\"" : null;

        if (!$alt && $caption) {
            $alt = $caption;
        }

        if (!$href) {
            $pos = strpos($src, "?");
            $href = $pos ? substr($src, 0, $pos) : $src;
        }

        $start = null;
        $end = null;
        if (!$nolink) {
            $start = "<a href=\"{$href}\">";
            $end = "</a>";
        }

        // Import the file containing the svg-image
        /*
        $svg = null;

        if($path[0] != '/') {
        $path = self::$dir . '/' . $path;
        }

        if(is_file($path)) {
        $svg = file_get_contents($path);
        }
        else {
        $svg = "No such file: $path";
        }
        $html = <<<EOD
        <figure{$id}{$class}>
        {$svg}
        <figcaption markdown=1>{$caption}</figcaption>
        </figure>
        EOD;*/
/*
        $html = <<<EOD
<figure{$id}{$class}>
{$start}<img src="{$src}" alt="{$alt}"{$title}/>{$end}
<figcaption markdown=1>{$caption}</figcaption>
</figure>
EOD;

        return $html;*/
/*    }
*/



/**
 * Shorttags to to quicker format text as HTML.
 *
 * @param string text text to be converted.
 * @return string the formatted text.
 */
/*public static function ShortTags($text) {
  $callback = function($matches) {
    switch($matches[1]) {
      case 'IMG':
        $caption = t('Image: ');
        $pos = strpos($matches[2], '?');
        $href = $pos ? substr($matches[2], 0, $pos) : $matches[2];
        $src = htmlspecialchars($matches[2]);
        return <<<EOD
<figure>
<a href='{$href}'><img src='{$src}' alt='{$matches[3]}' /></a>
<figcaption markdown=1>{$caption}{$matches[3]}</figcaption>
</figure>
EOD;

      case 'IMG2':
        $caption = null; //t('Image: ');
        $pos = strpos($matches[2], '?');
        $href = $pos ? substr($matches[2], 0, $pos) : $matches[2];
        $src = htmlspecialchars($matches[2]);
        return <<<EOD
<figure class="{$matches[4]}">
<a href='{$href}'><img src='{$src}' alt='{$matches[3]}' /></a>
<figcaption markdown=1>{$caption}{$matches[3]}</figcaption>
</figure>
EOD;
      case 'BOOK':
        $isbn = $matches[2];
        $stores = array(
          'BTH' => "http://bth.summon.serialssolutions.com/?#!/search?ho=t&amp;q={$isbn}",
          'Libris' => "http://libris.kb.se/hitlist?q={$isbn}",
          'Google Books' => "http://books.google.com/books?q={$isbn}",
          'Bokus' => "http://www.bokus.com/bok/{$isbn}",
          'Adlibris' => "http://www.adlibris.com/se/product.aspx?isbn={$isbn}",
          'Amazon' => "http://www.amazon.com/s/ref=nb_ss?url=field-keywords={$isbn}",
          'Barnes&Noble' => "http://search.barnesandnoble.com/booksearch/ISBNInquiry.asp?r=1&IF=N&EAN={$isbn}",
        );
        $html = null;
        foreach($stores as $key => $val) {
          $html .= "<a href='$val'>$key</a> &bull; ";
        }
        return substr($html, 0, -8);
      break;

      case 'YOUTUBE':
        $caption = t('Figure: ');
        $height = ceil($matches[3] / (16/9));
        return <<<EOD
<figure>
<iframe width='{$matches[3]}' height='{$height}' src="http://www.youtube.com/embed/{$matches[2]}" frameborder="0"
allowfullscreen></iframe>
<figcaption>{$caption}{$matches[4]}</figcaption>
</figure>
EOD;
      break;

      case 'syntax=': return CTextFilter::SyntaxHighlightGeSHi($matches[3], $matches[2]); break;
      case '```': return CTextFilter::SyntaxHighlightGeSHi($matches[3], $matches[2]); break;
      //case 'syntax=': return "<pre>" . highlight_string($matches[3], true) . "</pre>"; break;
      //case 'INCL':  include($matches[2]); break;
      case 'INFO':  return "<div class='info' markdown=1>"; break;
      case '/INFO': return "</div>"; break;
      case 'BASEURL': return CLydia::Instance()->request->base_url; break;
      case 'FIGURE': return CTextFilter::ShortCodeFigure($matches[2]); break;
      case 'FIGURE-SVG': return CTextFilter::ShortCodeSVGFigure($matches[2]); break;
      case 'ASCIINEMA': return CTextFilter::ShortCodeAsciinema($matches[2]); break;
      default: return "{$matches[1]} IS UNKNOWN SHORTTAG."; break;
    }
  };
  $patterns = array(
    '#\[(BASEURL)\]#',
    //'/\[(AUTHOR) name=(.+) email=(.+) url=(.+)\]/',
    '/\[(FIGURE)[\s+](.+)\]/',
    '/\[(FIGURE-SVG)[\s+](.+)\]/',
    '/\[(ASCIINEMA)[\s+](.+)\]/',
    '/\[(IMG) src=(.+) alt=(.+)\]/',
    '/\[(IMG2) src=(.+) alt="(.+)" class="(.+)"\]/',
    '/\[(BOOK) isbn=(.+)\]/',
    '/\[(YOUTUBE) src=(.+) width=(.+) caption=(.+)\]/',
    '/~~~(syntax=)(php|html|html5|css|sql|javascript|bash)\n([^~]+)\n~~~/s',
    '/(```)(php|html|html5|css|sql|javascript|bash|text|txt|python)\n([^`]+)\n```/s',
    //'/\[(INCL)/s*([^\]+)/',
    '#\[(INFO)\]#', '#\[(/INFO)\]#',
  );

  $ret = preg_replace_callback($patterns, $callback, $text);
  return $ret;
}
*/
}
