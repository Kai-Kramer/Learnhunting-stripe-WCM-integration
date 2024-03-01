<?php get_header(); ?>

<?php get_template_part("parts/header/page") ?>

<article class="gecko-theme-single">

    <header class="gecko-theme-single__header">
        <h1><?= get_the_title() ?></h1>

        <div class="gecko-theme-single__meta">
            <?= get_the_date() ?>
        </div>
    </header>

    <div class="gecko-theme-single__content">
        <?php the_content() ?>
    </div>

</article>

<?php get_footer();
