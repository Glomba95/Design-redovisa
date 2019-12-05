<?php

namespace Anax\TextFilter;

/**
 * Utilities when working with text.
 *
 */
trait TTextUtilities
{
    /**
     * Get text until <!--stop--> or all text.
     *
     * @param string $text with content
     *
     * @return string with text
     */
    public function getUntilStop($text)
    {
        $pos = stripos($text, "<!--stop-->");
        if ($pos) {
            $text = substr($text, 0, $pos);
        }
        return $text;
    }



    /**
     * Get text until <!--more--> or all text.
     *
     * @param string $text with content
     *
     * @return array with text and boolean if more was detected.
     */
    public function getUntilMore($text)
    {
        $pos = stripos($text, "<!--more-->");
        $hasMore = $pos;
        if ($pos) {
            $text = substr($text, 0, $pos);
        }
        return [$text, $hasMore];
    }



    /**
     * Wrap HTML element with with start and end.
     *
     * @param string  $text  with content
     * @param string  $tag   HTML tag to search for
     * @param string  $start wrap start part
     * @param string  $end   wrap end part
     * @param number  $count hits to search for
     *
     * @return array with text and boolean if more was detected.
     */
    public function wrapElementWithStartEnd($text, $tag, $start, $end, $count)
    {
        return preg_replace(
            "#(<$tag>)(.*?)(</$tag>)#",
            "$start$1$2$3$end</a>",
            $text,
            $count
        );
    }



    /**
    * Wrap content of a HTML element with start and end.
     *
     * @param string  $text  with content
     * @param string  $tag   HTML tag to search for
     * @param string  $start wrap start part
     * @param string  $end   wrap end part
     * @param number  $count hits to search for
     *
     * @return array with text and boolean if more was detected.
     */
    public function wrapElementContentWithStartEnd($text, $tag, $start, $end, $count)
    {
        return preg_replace(
            "#(<$tag>)(.*?)(</$tag>)#",
            "$1$start$2$end$3",
            $text,
            $count
        );
    }



    /**
     * Create a TOC of HTML headings from and to a certain level.
     *
     * @param string  $text  with content
     * @param integer $start level of headings to use for toc.
     * @param integer $stop  level of headings to use for toc.
     *
     * @return array with entries to generate a TOC.
     */
    public function createToc($text, $start = 2, $stop = 4)
    {
        $level = "$start-$stop";
        $pattern = "#<(h[$level])([^>]*)>(.*)</h[$level]>#";
        preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);

        $toc = [];
        foreach ($matches as $val) {
            preg_match("#id=['\"]([^>\"']+)#", $val[2], $id);
            $id = isset($id[1]) ? $id[1] : null;
            $toc[] = [
                "level" => isset($val[1])
                    ? $val[1]
                    : null,
                "title" => isset($val[3])
                    ? ltrim(strip_tags($val[3]), "#")
                    : null,
                "id" => $id,
            ];
        }

        return $toc;
    }



    /**
     * Create a anchor for each header having an id.
     *
     * @param string  $text  with content
     * @param integer $start level of headings to use.
     * @param integer $stop  level of headings to use.
     *
     * @return string with modified text.
     */
    public function createAnchor4Header($text, $start = 1, $stop = 4)
    {
        $level = "$start-$stop";
        $pattern = "#(<h[$level] id=\"([\w\-_]+)\">)(.+)(</h[$level]>)#";

        return preg_replace(
            $pattern,
            "$1<a class=\"header-anchor\" href=\"#$2\">#</a>$3$4",
            $text
        );
    }



    /**
     * Add baseurl to all relative links.
     *
     * @param string   $text     with content.
     * @param string   $baseurl  as string to prepend relative link.
     * @param callable $callback Use to create url from route.
     *
     * @return string with modified text.
     */
    public function addBaseurlToRelativeLinks($text, $baseurl, $callback)
    {
        $pattern = "#<a(.+?)href=\"([^\"]*)\"([.^>]*)>#";

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($baseurl, $callback) {
                $url = $callback($matches[2], $baseurl);
                return "<a${matches[1]}href=\"$url\"${matches[3]}>";
            },
            $text
        );
    }



    /**
     * Add baseurl to all relative links in image source.
     *
     * @param string   $text     with content.
     * @param string   $baseurl  as string to prepend relative link.
     * @param callable $callback Use to create url from route.
     *
     * @return string with modified text.
     */
    public function addBaseurlToImageSource($text, $baseurl, $callback)
    {
        $pattern = "#<img(.+?)src=\"([^\"]*)\"(.*?)>#";
        
        return preg_replace_callback(
            $pattern,
            function ($matches) use ($baseurl, $callback) {
                $url = $callback($matches[2], $baseurl);
                return "<img${matches[1]}src=\"$url\"${matches[3]}>";
            },
            $text
        );
    }



    /**
     * Generate revision history and add to the end of content.
     *
     * @param string $text     with content.
     * @param array  $revision with all revisions.
     * @param string $start    start wrap with this.
     * @param string $end      end wrap with this.
     * @param string $class    to add to ul element.
     * @param string $source   optional url to document source.
     *
     * @return string with text and optionally added revision history.
     */
    public function addRevisionHistory($text, $revision, $start, $end, $class, $source = null)
    {
        
        $text  = $text . $start;
        $text .= "<ul class=\"$class\">\n";
        
        foreach ($revision as $date => $info) {
            $text .= "<li>$date: $info</li>\n";
        }

        $text .= "</ul>\n";

        if ($source) {
            $text .= "<p><a class=\"$class\" href=\"$source\">"
            . t("Document source")
            . "</a>.</p>\n";
        }

        $text .= $end;

        return $text;
    }



    /**
     * Get content as pure text.
     *
     * @return string with the pure text.
     */
/*    public function GetPureText() {
      return preg_replace('/\s+/', ' ', strip_tags($this->GetFilteredData()));
    }
*/



    /**
     * Returns the excerpt of the text with at most the specified amount of characters.
     *
     * @param int $chars the number of characters to return.
     * @param boolean $hard do a hard break at exactly $chars characters or find closest space.
     * @return string as the excerpt.
     */
/*    public function GetExcerpt($chars=139, $hard=false) {
      if(!isset($this->data['data_filtered'])) {
        return null;
      }
      $excerpt = strip_tags($this->data['data_filtered']);

      if(strlen($excerpt) > $chars) {
        $excerpt   = substr($excerpt, 0, $chars-1);
      }

      if(!$hard) {
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt   = substr($excerpt, 0, $lastSpace);
      }

      return $excerpt;
    }

    /**
     * Returns the first paragraph ot the text.
     *
     * @return string as the first paragraph.
     */
/*    public function GetFirstParagraph() {
      if(!isset($this->data['data_filtered'])) {
        return null;
      }
      $excerpt = $this->data['data_filtered'];

      $firstPara = strpos($excerpt, '</p>');
      $excerpt   = substr($excerpt, 0, $firstPara + 4);

      return $excerpt;
    }
*/
}
