import React from "react";

export default function Loading(props) {
	const { active } = props;

	return (
		<div class="lds-spinner-wrapper" data-active={active ? "true" : "false"}>
			<div class="lds-spinner">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
	);
}
