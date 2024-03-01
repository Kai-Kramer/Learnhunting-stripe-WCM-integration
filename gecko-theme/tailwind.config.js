const colors = require("tailwindcss/colors");

module.exports = {
	content: [
		"./blocks/**/*.{php,js,jsx}",
		"./scripts/**/*.js",
		"./parts/**/*.php",
		"./functions/**/*.php",
		"./woocommerce/**/*.php",
		"./header.php",
		"./footer.php",
		"./404.php",
		"./index.php",
		"./page.php",
		"./search.php",
		"./single.php",
	],
	theme: {
		extend: {
			typography: {
				DEFAULT: {
					css: {
						color: "var(--wp--preset--color--foreground)",
						h2: {
							marginTop: 0,
							color: "var(--wp--preset--color--foreground)",
						},
						h3: {
							marginTop: 0,
							color: "var(--wp--preset--color--foreground)",
						},
						h4: {
							marginTop: 0,
							color: "var(--wp--preset--color--foreground)",
						},
						h5: {
							marginTop: 0,
							color: "var(--wp--preset--color--foreground)",
						},
						h6: {
							marginTop: 0,
							color: "var(--wp--preset--color--foreground)",
						},
						a: {
							transitionProperty: "color",
							transitionDuration: ".3s",
							transitionTimingFunction: "ease-in-out",
							color: "var(--wp--preset--color--secondary)",
							"&:hover": {
								color: "var(--wp--preset--color--secondary-dark)",
							},
						},
						"ol > li::marker": {
							color: "var(--wp--preset--color--secondary)",
						},
						"ul > li::marker": {
							color: "var(--wp--preset--color--secondary)",
						},
					},
				},
			},
		},
		colors: {
			...colors,
			transparent: "transparent",
			primary: "#ff6700",
			"primary-dark": "#d95c07",
			secondary: "#4e9997",
			"secondary-dark": "#3d7978",
			tertiary: "#264748",
			"tertiary-dark": "#1b3234",
			"gray-light": "#f6f6f6",
			"gray-dark": "#e7e7e7",
			foreground: "#212121",
			background: "#fefefe",
		},
		fontFamily: {
			sans: ["Bio Sans", "sans-serif"],
		},
	},
	variants: {
		extend: {},
	},
	plugins: [require("@tailwindcss/typography"), require("@tailwindcss/forms")],
};
