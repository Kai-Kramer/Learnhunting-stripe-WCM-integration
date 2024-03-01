import ReactDom from "react-dom";

import AdminEditProfile from "./AdminEditProfile/";

document.addEventListener("DOMContentLoaded", (e) => {
	const element = document.getElementById("learnhunting-admin-edit-profile");

	if (element) {
		const attributes = JSON.parse(element.getAttribute("data-props"));
		ReactDom.render(<AdminEditProfile {...attributes} />, element);
	}
});
