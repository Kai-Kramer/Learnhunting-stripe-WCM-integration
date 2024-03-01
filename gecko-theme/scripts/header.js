import { throttle } from "lodash";

// Find the header element
const header = document.querySelector(".primary-header");

const createFixedHeaderPadding = false;

// Continue if the header should be sticky
if (header && createFixedHeaderPadding) {
	// Don't add fixedHeaderPadding height on home page
	const isHome = document.body.classList.contains("home") ? true : false;

	// Create a new element to hold blank space for the fixed header
	const fixedHeaderPadding = document.createElement("div");
	fixedHeaderPadding.id = "fixed-header-padding";

	// Add the new element to the top of the body
	document.querySelector("body").prepend(fixedHeaderPadding);

	// Update the fixed header padding to match the height of the fixed header
	const setFixedHeaderHeight = () => {
		fixedHeaderPadding.style.height = isHome ? 0 : `${header.offsetHeight}px`;
		header.classList.add("primary-header--sticky");
	};

	// Attach height update event to various events where header could change height
	document.addEventListener("DOMContentLoaded", setFixedHeaderHeight);
	window.addEventListener("resize", setFixedHeaderHeight);
	window.addEventListener("orientationchange", setFixedHeaderHeight);
}

if (header) {
	// header.classList.add("primary-header--sticky");

	const asideOverlay = document.querySelector(".aside-menu-overlay");
	const asideMenu = document.querySelector(".aside-menu");
	const toggleButton = header.querySelector(".primary-header__toggle");
	const closeButtons = document.querySelectorAll(
		'[data-action="close-aside"]'
	);

	const closeMenu = () => {
		asideOverlay.setAttribute("data-active", "false");
		asideMenu.setAttribute("data-active", "false");
	};

	asideOverlay.addEventListener("click", closeMenu);

	if (closeButtons) {
		closeButtons.forEach((button) => {
			button.addEventListener("click", closeMenu);
		});
	}

	const openMenu = () => {
		asideOverlay.setAttribute("data-active", "true");
		asideMenu.setAttribute("data-active", "true");
	};
	toggleButton.addEventListener("click", openMenu);
}

const debounceScrollSpy = throttle(() => {
	if (window.scrollY > 100) {
		document.body.classList.add("scrolled");
	} else {
		document.body.classList.remove("scrolled");
	}
}, 100);

addEventListener("scroll", debounceScrollSpy);
