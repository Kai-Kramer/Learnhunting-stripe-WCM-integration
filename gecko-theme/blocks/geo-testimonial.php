<?php
$state_abbr = do_shortcode("[geoip-region]");

$default_image = false;
if ($image = get_field("default_image")) {
    $default_image = $image["sizes"]["large"];
}
$default_quote = get_field("default_quote") ?? "";
$default_citation = get_field("default_citation") ?? "";

// Don't display the block if there is no agency logo
if (empty($default_image) || empty($default_quote)) {
    echo "<strong>Geo Testimonial:</strong> Missing required image or text content";
    return;
}

$image_url = $default_image;
$quote_text = $default_quote;
$citation_text = $default_citation;

if (!empty($state_abbr) && $states = get_field("states", "options")) {
    $state_images = [];
    foreach ($states as $state) {
        if ($state_abbr === $state["abbreviation"]) {
            if (!empty($state["testimonial_image"]["sizes"]["large"])) {
                $image_url = $state["testimonial_image"]["sizes"]["large"];
            }

            if (!empty($state["testimonial_content"])) {
                $quote_text = $state["testimonial_content"];

                if (!empty($state["testimonial_citation"])) {
                    $citation_text = $state["testimonial_citation"];
                } else {
                    $citation_text = "";
                }
            }
        }
    }
}
?>

<div class="gecko-geo-testimonial" data-image-order="<?= get_field("image_order") ?>" data-hide-image="<?= get_field("hide_image") ? "true" : "false" ?>" data-style="<?= get_field("style") ?>">
    <div class="gecko-geo-testimonial__image" style="background-image: url('<?= $image_url ?>');"></div>

    <figure class="gecko-geo-testimonial__quote">
        <blockquote>
            <svg viewBox="0 0 391 340" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 339.966H160.893V178.164H102.717V147.258C102.717 101.808 121.806 81.81 160.893 81.81V0C45.45 0 0 52.722 0 174.528V339.966ZM229.977 339.966H390.87V178.164H332.694V147.258C332.694 101.808 351.783 81.81 390.87 81.81V0C275.427 0 229.977 52.722 229.977 174.528V339.966Z" fill="#FF6700"/>
            </svg>

            <div><?= $quote_text ?></div>
        </blockquote>

        <?php if (!empty($citation_text)): ?>
            <cite><?= $citation_text ?></cite>
        <?php endif ?>
    </figure>
</div>
