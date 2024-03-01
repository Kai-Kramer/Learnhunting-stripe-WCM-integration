	</section>
</main>

<footer class="gecko-footer">
	<div class="gecko-footer__row">
		<div class="gecko-footer__logo">
			<?php get_template_part("images/logo-footer.svg") ?>
		</div>

		<?php if (has_nav_menu("footer")): ?>
			<div class="gecko-footer__nav">
				<?php wp_nav_menu(array("theme_location" => "footer")); ?>
			</div>
		<?php endif ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
