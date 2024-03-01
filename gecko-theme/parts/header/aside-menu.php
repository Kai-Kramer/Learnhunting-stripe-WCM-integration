<div class="aside-menu-overlay"></div>

<aside class="aside-menu" data-active="false">

    <header class="aside-menu__branding">
        <?php get_template_part("parts/header/branding") ?>
    </header>

    <section class="aside-menu__navigation">
        <div class="aside-menu__secondary">
            <nav>
                <?php if (is_user_logged_in()): ?>
                    <a href="/my-account">My Account</a>
                    <a href="<?= wp_logout_url(home_url()) ?>">Logout</a>
                <?php else: ?>
                    <a href="/my-account">Login</a>
                <?php endif ?>
            </nav>
        </div>

        <?php wp_nav_menu(array("theme_location" => "primary")); ?>
    </section>

</aside>
