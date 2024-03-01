import React, { useState } from "react";

import Modal from "./Modal";

export default function AdminEditProfile(props) {
	const { userId, game_types, hunting_types, instructor_types } = props;

	const [modalActive, setModalActive] = useState(false);

	return (
		<>
			<button
				className="button-primary fixed bottom-6 right-6 z-[99999]"
				type="button"
				style={{
					display: "block",
					paddingTop: "0.5rem",
					paddingBottom: "0.5rem",
					paddingLeft: "2rem",
					paddingRight: "2rem",
					lineHeight: "1.5",
					fontSize: "1.25rem",
					borderRadius: "99rem",
				}}
				onClick={(e) => {
					e.preventDefault();
					setModalActive(!modalActive);
				}}
			>
				Update LearnHunting.org
				<br />
				Profile Details
			</button>

			<Modal
				modalActive={modalActive}
				setModalActive={setModalActive}
				userId={userId}
				gameTypes={game_types}
				huntingTypes={hunting_types}
				instructorTypes={instructor_types}
			/>
		</>
	);
}
