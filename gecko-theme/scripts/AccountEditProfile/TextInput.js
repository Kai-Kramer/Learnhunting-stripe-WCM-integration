import React from "react";

export default function TextInput(props) {
	const {
		type,
		label,
		name,
		required,
		readOnly,
		profileDetails,
		handleInputChange,
		isSaving,
		hasError,
	} = props;

	const inputType = type || "text";

	const inputClasses = ["m-0", "block", "w-full"];
	const readOnlyClasses = ["border-gray-200", "text-gray-600"];
	const errorClasses = [
		"border-red-500",
		"focus:border-red-500",
		"focus:ring-red-500",
		"bg-red-50",
	];

	if (readOnly) {
		inputClasses.push(readOnlyClasses);
	}

	if (hasError) {
		inputClasses.push(errorClasses);
	}

	return (
		<>
			<label className="mb-1 block font-bold" htmlFor={name}>
				{label}:
			</label>

			<input
				type={inputType}
				className={inputClasses.join(" ")}
				value={profileDetails[name]}
				readOnly={readOnly}
				required={required}
				id={name}
				name={name}
				onChange={handleInputChange}
				disabled={isSaving}
			/>
		</>
	);
}
