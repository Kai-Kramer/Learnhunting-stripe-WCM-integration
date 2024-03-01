<?php
$style = "";
if ($bg_image = get_field("page_header_background_image", "options")) {
    $style = "background-image: url('{$bg_image["url"]}');";
}

$title = get_the_title();
if (!empty($args["title"])) {
    $title = $args["title"];
}
?>

<div class="gecko-page-header" style="<?= $style ?>">
    <div class="gecko-page-header__frame-wrapper">
        <div class="gecko-page-header__frame"></div>
    </div>

    <div class="gecko-page-header__row">
        <h1><?= $title ?></h1>

        <div class="gecko-page-header__logos">
            <?php get_template_part("parts/geo-logos"); ?>
        </div>
    </div>
</div>
