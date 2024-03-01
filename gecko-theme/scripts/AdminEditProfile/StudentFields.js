import React, { useState, useEffect } from "react";

import TextInput from "./TextInput";

export default function StudentFields(props) {
	const {
		errors,
		isSaving,
		profileDetails,
		setProfileDetails,
		handleInputChange,
	} = props;

	const fieldHasError = (name) => {
		return errors && Object.keys(errors).includes(name);
	};

	return (
		<>
			<div className="mb-8 grid grid-cols-12 gap-4 md:gap-8">
				<div className="col-span-12 sm:col-span-4">
					<TextInput
						label="Date of Birth"
						name="birthYear"
						type="date"
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("birthYear")}
					/>
				</div>
			</div>
		</>
	);
}
