<?php

add_shortcode("geoip-agency", function() {
    $state_abbr = do_shortcode("[geoip-region]");

    $agency_name = "";

    if (!empty($state_abbr) && $states = get_field("states", "options")) {
        foreach ($states as $state) {
            if ($state_abbr === $state["abbreviation"]) {
                if (!empty($state["agency_name"])) {
                    $agency_name = $state["agency_name"];
                }
            }
        }
    }

    return $agency_name;
});