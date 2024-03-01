import React, { useState } from "react";

import Dropzone from "./Dropzone";

export default function Avatar(props) {
	const { avatarDetails, getInitialValues, userId } = props;

	const [formActive, setFormActive] = useState(false);

	return (
		<>
			<button
				className="mb-6 flex cursor-pointer items-center gap-2 rounded-full border-none bg-transparent p-0 font-normal normal-case text-foreground hover:bg-transparent hover:text-foreground"
				onClick={(e) => setFormActive(true)}
			>
				{avatarDetails && avatarDetails.url && (
					<div
						className="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-full bg-stone-200 bg-cover bg-center"
						style={{
							backgroundImage: `url(${avatarDetails.url})`,
						}}
					/>
				)}

				{avatarDetails && avatarDetails.monogram && !avatarDetails.url && (
					<div className="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-full bg-secondary text-xl text-white">
						{avatarDetails.monogram}
					</div>
				)}

				<div className="bg-transparent p-2 text-sm text-foreground">
					Update Profile Image
				</div>
			</button>

			{formActive && (
				<Dropzone
					getInitialValues={getInitialValues}
					setFormActive={setFormActive}
					userId={userId}
				/>
			)}
		</>
	);
}
