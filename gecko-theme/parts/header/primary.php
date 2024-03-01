<header
    class="primary-header primary-header--sticky"
    data-sticky="true"
    >

    <div class="primary-header__row">
        <div class="primary-header__branding">
            <?php get_template_part("parts/header/branding") ?>
        </div>

        <div class="primary-header__spacer"></div>

        <div class="primary-header__navigation">
            <div class="primary-header__secondary">
                <nav class="account-header">
                    <?php if (is_user_logged_in() && $current_user = wp_get_current_user()): ?>
                        <a class="account-header__link" href="/<?= wc_logout_url("my-account") ?>">Logout</a>

                        <?php if (current_user_can('administrator')): ?>
                           <a class="account-header__link" href="<?= admin_url() ?>">Admin</a>
                        <?php endif ?>

                        <a class="account-header__link" href="/my-account">My Account</a>
                    <?php else: ?>
                        <a href="/my-account">Login</a>
                    <?php endif ?>
                </nav>
            </div>

            <?php wp_nav_menu(array("theme_location" => "primary")); ?>
        </div>

        <button class="primary-header__toggle">
            <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M57.5 27.5H2.5C1.83696 27.5 1.20107 27.7634 0.732233 28.2322C0.263392 28.7011 0 29.337 0 30C0 30.663 0.263392 31.2989 0.732233 31.7678C1.20107 32.2366 1.83696 32.5 2.5 32.5H57.5C58.163 32.5 58.7989 32.2366 59.2678 31.7678C59.7366 31.2989 60 30.663 60 30C60 29.337 59.7366 28.7011 59.2678 28.2322C58.7989 27.7634 58.163 27.5 57.5 27.5ZM2.5 15H57.5C58.163 15 58.7989 14.7366 59.2678 14.2678C59.7366 13.7989 60 13.163 60 12.5C60 11.837 59.7366 11.2011 59.2678 10.7322C58.7989 10.2634 58.163 10 57.5 10H2.5C1.83696 10 1.20107 10.2634 0.732233 10.7322C0.263392 11.2011 0 11.837 0 12.5C0 13.163 0.263392 13.7989 0.732233 14.2678C1.20107 14.7366 1.83696 15 2.5 15V15ZM57.5 45H2.5C1.83696 45 1.20107 45.2634 0.732233 45.7322C0.263392 46.2011 0 46.837 0 47.5C0 48.163 0.263392 48.7989 0.732233 49.2678C1.20107 49.7366 1.83696 50 2.5 50H57.5C58.163 50 58.7989 49.7366 59.2678 49.2678C59.7366 48.7989 60 48.163 60 47.5C60 46.837 59.7366 46.2011 59.2678 45.7322C58.7989 45.2634 58.163 45 57.5 45Z"/>
            </svg>
        </button>
    </div>
</header>
