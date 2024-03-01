<?php
if (is_wc_endpoint_url('edit-account')) {
    return;
}

$current_user = wp_get_current_user();
$first_name = get_user_meta($current_user->ID, 'billing_first_name', true);
$last_name = get_user_meta($current_user->ID, 'billing_last_name', true);
$monogram = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));

$profile_image = null;

if ($avatar_filename = get_user_meta($current_user->ID, "avatar_image_filename", true)) {
    $profile_image = wp_get_upload_dir()["baseurl"] . "/user-avatars/" . $avatar_filename;
}
?>
<a href="<?= wc_get_endpoint_url("edit-account") ?>" class="learnhunting-dashboard-welcome">
    <div>
        <?php if (!empty($profile_image)): ?>
            <div class="learnhunting-dashboard-welcome__image" style="background-image: url(<?= $profile_image ?>);"></div>
        <?php else: ?>
            <div class="learnhunting-dashboard-welcome__monogram">
                <?= $monogram ?>
            </div>
        <?php endif ?>
    </div>

    <div class="learnhunting-dashboard-welcome__content">
        Welcome,
        <strong><?= $first_name ?></strong>
    </div>
</a>
