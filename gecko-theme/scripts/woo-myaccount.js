import ReactDom from "react-dom";

import AccountEditProfile from "./AccountEditProfile/";
import AccountChangePassword from "./AccountChangePassword/";
import AccountUpdateAvailability from "./AccountUpdateAvailability";
import AccountLogMentorHours from "./AccountLogMentorHours";

document.addEventListener("DOMContentLoaded", (e) => {
	const element = document.getElementById("learnhunting-account-edit-profile");

	if (element) {
		const attributes = JSON.parse(element.getAttribute("data-props"));
		ReactDom.render(<AccountEditProfile {...attributes} />, element);
	}
});

document.addEventListener("DOMContentLoaded", (e) => {
	const element = document.getElementById(
		"learnhunting-account-update-password"
	);

	if (element) {
		const attributes = JSON.parse(element.getAttribute("data-props"));
		ReactDom.render(<AccountChangePassword {...attributes} />, element);
	}
});

document.addEventListener("DOMContentLoaded", (e) => {
	const element = document.getElementById(
		"learnhunting-account-update-availability"
	);

	if (element) {
		const attributes = JSON.parse(element.getAttribute("data-props"));
		ReactDom.render(<AccountUpdateAvailability {...attributes} />, element);
	}
});

document.addEventListener("DOMContentLoaded", (e) => {
	const element = document.getElementById(
		"learnhunting-account-log-mentor-hours"
	);

	if (element) {
		const attributes = JSON.parse(element.getAttribute("data-props"));
		ReactDom.render(<AccountLogMentorHours {...attributes} />, element);
	}
});

document.addEventListener("DOMContentLoaded", function (event) {
	const sidebar = document.querySelector(
		".learnhunting-dashboard__navigation"
	);

	if (sidebar) {
		const button = sidebar.querySelector(".learnhunting-dashboard__toggle");
		const menu = sidebar.querySelector(
			".learnhunting-dashboard__menu--primary"
		);

		if (button && menu) {
			const toggleMenu = (e) => {
				e.preventDefault();

				if (menu.getAttribute("data-active") === "true") {
					menu.setAttribute("data-active", "false");
				} else {
					menu.setAttribute("data-active", "true");
				}
			};

			button.addEventListener("click", toggleMenu);
		}
	}
});
