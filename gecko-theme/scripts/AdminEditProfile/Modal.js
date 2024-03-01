import React from "react";
import { createPortal } from "react-dom";
import App from "./App";

export default function Modal(props) {
	const {
		modalActive,
		setModalActive,
		userId,
		gameTypes,
		huntingTypes,
		instructorTypes,
	} = props;

	if (modalActive) {
		return createPortal(
			<div className="fixed top-0 left-0 z-[999999] flex h-full w-full flex-col items-center justify-center bg-slate-900/75">
				<div
					className="absolute top-0 left-0 z-10 flex h-full w-full"
					onClick={() => setModalActive(false)}
				/>

				<div className="relative z-20 h-full max-h-[600px] w-full max-w-2xl overflow-auto rounded bg-white p-8">
					<App
						userId={userId}
						gameTypes={gameTypes}
						huntingTypes={huntingTypes}
						instructorTypes={instructorTypes}
					/>
				</div>
			</div>,
			document.body
		);
	}

	return <></>;
}
