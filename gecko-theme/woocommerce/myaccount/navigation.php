<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dashboard = new LearnHuntingAccountDashboard();
$is_student = $dashboard->is_student;
$is_mentor = $dashboard->is_mentor;

$journeyage_token = "";
if ($token = $dashboard->get_journeyage_token()) {
    $journeyage_token = $token;
}

$navigation_links_slug = 'default_navigation_links';
if ($is_student) {
	$navigation_links_slug = 'student_navigation_links';
} elseif ($is_mentor) {
	$navigation_links_slug = 'mentor_navigation_links';
}
?>

<nav class="learnhunting-dashboard__navigation">
	<button class="learnhunting-dashboard__toggle">
		<svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M41 20V24C41 24.552 40.553 25 40 25H4C3.447 25 3 24.552 3 24V20C3 19.448 3.447 19 4 19H40C40.553 19 41 19.448 41 20ZM40 31H4C3.447 31 3 31.448 3 32V36C3 36.552 3.447 37 4 37H40C40.553 37 41 36.552 41 36V32C41 31.448 40.553 31 40 31ZM40 7H4C3.447 7 3 7.448 3 8V12C3 12.552 3.447 13 4 13H40C40.553 13 41 12.552 41 12V8C41 7.448 40.553 7 40 7Z" fill="#222222" />
		</svg>

		<span>Menu</span>
	</button>

	<ul class="learnhunting-dashboard__menu learnhunting-dashboard__menu--primary" data-active="false">
		<li>
			<?php get_template_part("parts/dashboard-welcome") ?>
		</li>

		<?php if ($links = get_field("organization_adminstrator_links", "user_".get_current_user_id())): ?>
			<li>
				<ul class="learnhunting-dashboard__menu">
					<?php foreach($links as $link): ?>
						<li class="learnhunting-dashboard__menu-item">
							<?php
							$url = $link["link"]["url"];
							$target = $link["link"]["target"];

							$icon = (!empty($link["icon"])) ? $link["icon"] : "default";
							?>
							<a href="<?= $url ?>" target="<?= $target ?>">
								<?php get_template_part("parts/icons/".$icon.".svg") ?>

								<span>
									<?= $link["link"]["title"] ?>

									<?php if (!empty($link["subtitle"])): ?>
										<small>
											<?= $link["subtitle"] ?>
										</small>
									<?php endif ?>
								</span>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
			</li>
		<?php endif ?>

		<?php if ($navigation_links = get_field($navigation_links_slug, "options")): ?>
			<?php foreach($navigation_links as $link): ?>
				<li class="learnhunting-dashboard__menu-item">
					<?php
					$url = $link["link"]["url"];
					$target = $link["link"]["target"];
					if ($link["journeyage_url"]) {
						// $url = $url . "?token=" . $journeyage_token;
						$url = add_query_arg("token", $journeyage_token, $url);
						$target = "_blank";
					}

					$icon = (!empty($link["icon"])) ? $link["icon"] : "default";
					?>
					<a href="<?= $url ?>" target="<?= $target ?>">
						<?php get_template_part("parts/icons/".$icon.".svg") ?>

						<span>
							<?= $link["link"]["title"] ?>

							<?php if (!empty($link["subtitle"])): ?>
								<small>
									<?= $link["subtitle"] ?>
								</small>
							<?php endif ?>
						</span>
					</a>
				</li>
			<?php endforeach ?>
		<?php endif ?>

		<?php if ($is_mentor): ?>
			<li>
				<?php get_template_part("parts/availability-widget") ?>
			</li>
		<?php endif ?>

		<li class="learnhunting-dashboard__menu-item learnhunting-dashboard__menu-item--has-children">
			<ul class="learnhunting-dashboard__sub-menu">
				<li class="learnhunting-dashboard__menu-item">
					<a href="<?= esc_url(wc_get_account_endpoint_url("edit-account")); ?>">Edit Profile</a>
				</li>

				<li class="learnhunting-dashboard__menu-item">
					<a href="<?= esc_url(wc_get_account_endpoint_url("update-password")); ?>">Change Password</a>
				</li>

				<li class="learnhunting-dashboard__menu-item">
					<a href="<?= esc_url(wc_get_account_endpoint_url("subscriptions")); ?>">Subscriptions</a>
				</li>

				<li class="learnhunting-dashboard__menu-item">
					<a href="<?= esc_url(wc_get_account_endpoint_url("orders")); ?>">Orders</a>
				</li>

				<?php if (false): ?>
					<li class="learnhunting-dashboard__menu-item">
						<a href="<?= esc_url(wc_get_account_endpoint_url("payment-methods")); ?>">Payment Methods</a>
					</li>
				<?php endif ?>

				<li class="learnhunting-dashboard__menu-item">
					<a href="<?= esc_url(wc_get_account_endpoint_url("customer-logout")); ?>">Logout</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
