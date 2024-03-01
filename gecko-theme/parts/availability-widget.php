<?php
global $wp_query;
if (isset($wp_query->query_vars['update-availability'])) {
    return;
}

$is_available = get_user_meta(get_current_user_id(), 'is_available', true) ? "true" : "false";
?>

<a href="<?= wc_get_endpoint_url("update-availability") ?>" class="learnhunting-availability-widget" data-is-available="<?= $is_available ?>">
    <div class="learnhunting-availability-widget__dot-wrapper">
        <div class="learnhunting-availability-widget__dot-icon">
            <svg
                viewBox="0 0 512 512"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M440.63 126.366C451.79 136.662 451.79 154.681 440.63 164.977L220.876 384.634C210.575 395.789 192.548 395.789 182.247 384.634L72.3696 274.806C61.2101 264.509 61.2101 246.491 72.3696 236.194C82.6706 225.04 100.697 225.04 110.998 236.194L201.132 326.288L402.002 126.366C412.303 115.211 430.329 115.211 440.63 126.366Z"
                    fill="black"
                />
            </svg>
        </div>
    </div>

    <div class="learnhunting-availability-widget__label">
        <?= ($is_available === "true") ? "Available" : "Unavailable" ?>
    </div>
</a>
