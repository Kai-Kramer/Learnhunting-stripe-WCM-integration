<?php
$grid_items = get_field("grid_items");

$dashboard = new LearnHuntingAccountDashboard();

$journeyage_token = "";
if ($token = $dashboard->get_journeyage_token()) {
    $journeyage_token = $token;
}
?>

<div class="learnhunting-dashboard-grid">
    <?php foreach($grid_items as $grid_item): ?>
        <?php
        $bg_style = "";
        $text_style = "";

        if ($grid_item["background_style"] === "color") {
            $bg_style = "background-color: " . $grid_item["background_color"] . ";";
            $text_style = "color: " . $grid_item["text_color"] . ";";
        } else if ($grid_item["background_style"] === "image") {
            $bg_style = "background-image: url(" . $grid_item["background_image"]["url"] . ");";
        }

        if (!empty($grid_item["text_color"])) {
            $text_style = "color: " . $grid_item["text_color"] . ";";
        }

        $style = $bg_style . $text_style;
        ?>

        <?php if ($grid_item["link_style"] === "journeyage" && !empty($grid_item["journeyage_link"]) && !$is_preview): ?>
            <?php $journeyage_url = add_query_arg('token', $token, $grid_item["journeyage_link"]); ?>
            <a href="<?= $journeyage_url ?>" target="_blank" class="learnhunting-dashboard-grid__item" style="<?= $style ?>" data-width="<?= $grid_item["width"] ?>">
        <?php elseif (!empty($grid_item["link"]) && !$is_preview): ?>
            <a href="<?= $grid_item["link"]["url"] ?>" target="<?= $grid_item["link"]["target"] ?>" class="learnhunting-dashboard-grid__item" style="<?= $style ?>" data-width="<?= $grid_item["width"] ?>">
        <?php else: ?>
            <div class="learnhunting-dashboard-grid__item" style="<?= $style ?>" data-width="<?= $grid_item["width"] ?>">
        <?php endif ?>

            <?php if ($grid_item["background_style"] === "image"): ?>
                <div class="learnhunting-dashboard-grid__item-overlay" style="<?= "background: " . $grid_item["background_image_overlay"] . ";" ?>"></div>
            <?php endif ?>

            <?php if (!empty($grid_item["title"])): ?>
                <h2><?= $grid_item["title"] ?></h2>
            <?php endif ?>

            <?php if (!empty($grid_item["subtitle"])): ?>
                <h3><?= $grid_item["subtitle"] ?></h3>
            <?php endif ?>

        <?php if ($grid_item["link_style"] === "journeyage" && !empty($grid_item["journeyage_link"]) && !$is_preview): ?>
            </a>
        <?php elseif (!empty($grid_item["link"]) && !$is_preview): ?>
            </a>
        <?php else: ?>
            </div>
        <?php endif ?>

    <?php endforeach ?>
</a>
