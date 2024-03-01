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

$default_logo = false;
if ($default_image = get_field("default_image")) {
    $default_logo = $default_image["url"];
}
// Don't display the block if there is no agency logo
if (empty($state_logo) && empty($default_logo)) {
    return false;
}

$align = $block["align"] ?? "none";

$logo_url = (!empty($state_logo)) ? $state_logo : $default_logo;
?>

<div class="gecko-agency-logo" data-style="<?= get_field("style") ?>" data-align="<?= $align ?>">
    <div class="gecko-agency-logo__logo">
        <img src="<?= $logo_url ?>" />
    </div>
</div>
