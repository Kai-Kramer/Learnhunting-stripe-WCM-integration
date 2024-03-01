<?php
$state_abbr = do_shortcode("[geoip-region]");

$state_logo = false;
if (!empty($state_abbr) && $states = get_field("states", "options")) {
    $state_logos = [];
    foreach ($states as $state) {
        if (!empty($state["logo"]["sizes"]["large"])) {
            $state_logos[$state["abbreviation"]] = $state["logo"]["sizes"]["large"];
        }
    }

    if (!empty($state_logos[$state_abbr])) {
        $state_logo = $state_logos[$state_abbr];
    }
}
?>

<div class="gecko-geo-logos" data-has-state-logo="<?= (!empty($state_logo)) ? "true" : "false" ?>">
    <?php if ($state_logo): ?>
        <div class="gecko-geo-logos__logo">
            <img src="<?= $state_logo ?>" alt="<?= $state_logo ?> state logo" />
        </div>
    <?php endif ?>

    <div class="gecko-geo-logos__logo">
        <?php if ($state_logo): ?>
            <img src="<?= get_template_directory_uri() ?>/images/stacked-logo.png" alt="<?= get_bloginfo("name") ?>" />
        <?php else: ?>
            <?php get_template_part("images/ihea-usa-logo.svg") ?>
        <?php endif ?>
    </div>
</div>
