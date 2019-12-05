<?php
// Prepare classes
$classes[] = "share";
if (isset($class)) {
    $classes[] = $class;
}



// Create the shares
$url = $this->di->get("request")->getCurrentUrl();
$title = urlencode($this->di->get("theme")->getVariable("title"));

$shares = [
    "facebook" => [
        "icon" => "fa-facebook-square",
        "url" => "http://www.facebook.com/sharer.php?u=$url&t=$title",
    ],
    "twitter"  => [
        "icon" => "fa-twitter-square",
        "url" => "http://twitter.com/share?url=$url&text=$title",
    ],
    "google+"  => [
        "icon" => "fa-google-plus-square",
        "url" => "https://plus.google.com/share?url=$url",
    ],
    "linkedin" => [
        "icon" => "fa-linkedin-square",
        "url" => "https://www.linkedin.com/cws/share?url=$url&title=$title",
    ],
];



// Create the sharestring
$shareStr = "";
foreach ($shares as $share) {
    $shareStr .= <<<EOD
<a href="${share["url"]}"><i class="fa ${share["icon"]} fa-2x" aria-hidden="true"></i></a> 
EOD;
}
$shareStr = t("Share this on !SHARE", ["!SHARE" => $shareStr]);



?><div <?= $this->classList($classes) ?>>
<p><?= $shareStr ?></p>
</div>
