<div class="wrap">
	<h1 class="wp-heading-inline">Theme Parts Help</h1>
	<p><strong>Gecko Theme Parts</strong> allows you to use the Block Editor to create content anywhere in a theme, outside of the page content loop.</p>

	<h2>Registering a new Theme Part Location</h2>
	<p>In order to use Theme Parts in your theme you will need to register the Locations in your theme's template files where you would like to output the associated Theme Part content.</p>
	<p>Add the following code to your theme's <code>functions.php</code> file to register any number of locations.</p>

	<pre><code style="display: block; padding: 1rem">add_action("after_setup_theme", function() {
	add_filter('gecko_theme_parts_register_locations', function($locations) {
		$locations[] = [
			'name' => 'Footer',
			'description' => 'Primary footer area content',
		];

		$locations[] = [
			'name' => 'Sidebar',
			'description' => 'Sidebar content',
		];

		return $locations;
	}, 10, 1);
});</code></pre>

	<h2>Output Theme Part content in your theme's template</h2>
	<p>Add the following code to any of your theme's template files to output the block editor content in that spot.</p>

	<pre><code style="display: block; padding: 1rem">&lt;footer class="my-theme-footer"&gt;
	&lt;?php do_action('gecko_theme_parts_content', 'Footer') ?&gt;
&lt;/footer&gt;</code></pre>

	<h3>Next Steps</h3>
	<p>Now that you've set up the locations in your theme you can create a new Theme Part and assign it to one of the new locations.</p>
	<p><a class="button" href="<?= admin_url('post-new.php?post_type=gecko-theme-part') ?>">Add New Theme Part</a></p>
	<p><a class="button" href="<?= admin_url('edit.php?post_type=gecko-theme-part&page=gecko-theme-part-locations') ?>">Manage Locations</a></p>

</div>