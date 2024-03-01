// We use the DependencyExtractionWebpackPlugin to allow usage of wordpress libraries
const DependencyExtractionWebpackPlugin = require("@wordpress/dependency-extraction-webpack-plugin");
// TailwindCSS is available in the WordPress theme, make sure you build/purge before pushing to production
const tailwindcss = require("tailwindcss");
const sassGlobImporter = require("node-sass-glob-importer");
// Laravel Mix builds the distributable assets for the theme
const mix = require("laravel-mix");

// Configure tailwind to use our custom tailwind.config.js file
const tailwindOptions = {
	processCssUrls: false, // We don't want to process css urls, we want to use the WordPress url
	postCss: [tailwindcss("./tailwind.config.js")],
};

mix.webpackConfig({
	externals: {
		// Use the wordpress libraries instead of bundling them
		react: "React",
		"react-dom": "ReactDOM",
	},
	plugins: [
		new DependencyExtractionWebpackPlugin(), // Extract the wordpress libraries used in the bundle
	],
});

mix.disableNotifications();
mix.setPublicPath("./");
mix.browserSync({
	proxy: "https://learn-hunting.gecko",
	notify: false,
	files: ["./**/*.php"],
});

// The base 'theme.scss' file should contain
mix.sass("./styles/theme.scss", "./dist/theme.css", {
	sassOptions: {
		importer: sassGlobImporter(),
	},
})
	.options({ ...tailwindOptions })
	.version();

mix.sass("./styles/editor.scss", "./dist/editor.css", {
	sassOptions: {
		importer: sassGlobImporter(),
	},
})
	.options({ ...tailwindOptions })
	.version();

// We build tailwind-utilities separately because it takes forever to build
mix.sass(
	"./styles/tailwind-utilities.scss",
	"./dist/tailwind-utilities.css",
	{},
	[require("postcss-import"), require("tailwindcss")]
)
	.options({ ...tailwindOptions })
	.version();

mix.js("./scripts/theme.js", "./dist/theme.bundle.js").react().version();

mix.js("./scripts/editor.js", "./dist/editor.bundle.js").react().version();
