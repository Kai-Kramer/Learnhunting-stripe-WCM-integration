document.addEventListener("DOMContentLoaded", function (event) {
	const dismissableSections = document.querySelectorAll(
		'.gecko-theme-section[data-dismissable="true"][data-is-preview="false"]'
	);

	if (dismissableSections) {
		dismissableSections.forEach((section) => {
			const sectionId = section.getAttribute("id");

			const hasDismissed = localStorage.getItem(`dismissed-${sectionId}`);

			if (hasDismissed) {
				section.remove();
			} else {
				section.style.display = "block";
			}

			const dismissButton = section.querySelector(
				".gecko-theme-section__dismiss-button"
			);

			if (dismissButton) {
				dismissButton.addEventListener("click", () => {
					section.style.display = "none";
					localStorage.setItem(`dismissed-${sectionId}`, true);
				});
			}
		});
	}
});
