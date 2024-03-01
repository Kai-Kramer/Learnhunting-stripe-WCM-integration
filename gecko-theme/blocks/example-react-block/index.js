import ReactDom from "react-dom";

import App from "./app/";

document.addEventListener("DOMContentLoaded", (e) => {
	const elements = document.querySelectorAll(".example-react-block");

	if (elements) {
		elements.forEach((element) => {
			ReactDom.render(<App />, element);
		});
	}
});
