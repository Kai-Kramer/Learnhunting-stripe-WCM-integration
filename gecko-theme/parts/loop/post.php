<?php
$img_src = get_the_post_thumbnail_url(get_the_ID(), "medium");
?>

<div class="gecko-theme-loop-post">
    <?php if ($img_src && $img_src != ""): ?>
        <a href="<?= get_the_permalink() ?>" class="gecko-theme-loop-post__image" style="background-image: url('<?= $img_src ?>');"></a>
    <?php endif ?>

    <div class="gecko-theme-loop-post__content">
        <h2>
            <a href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a>
        </h2>

        <p><?= get_the_excerpt() ?></p>

        <a href="<?= get_the_permalink()?>">Read More</a>
    </div>
</div>
