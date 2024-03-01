import React from "react";

import TextInput from "./TextInput";

export default function StandardFields(props) {
	const { errors, isSaving, profileDetails, handleInputChange } = props;

	const fieldHasError = (name) => {
		return errors && Object.keys(errors).includes(name);
	};

	return (
		<>
			<div className="mb-8 grid grid-cols-12 gap-4 md:gap-8">
				<div className="col-span-12 sm:col-span-6">
					<TextInput
						label="First Name"
						name="firstName"
						required={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
					/>
				</div>

				<div className="col-span-12 sm:col-span-6">
					<TextInput
						label="Last Name"
						name="lastName"
						required={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
					/>
				</div>

				<div className="col-span-12 sm:col-span-6">
					<TextInput
						type="email"
						label="Email"
						name="email"
						readOnly={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
					/>
				</div>

				<div className="col-span-12 sm:col-span-6">
					<TextInput
						label="Phone"
						name="phone"
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("phone")}
					/>
				</div>

				<div className="col-span-12 sm:col-span-6">
					<TextInput
						label="Street Address"
						name="address"
						required={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("address")}
					/>
				</div>

				<div className="col-span-12 sm:col-span-6">
					<TextInput
						label="Address 2"
						name="address2"
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("address2")}
					/>
				</div>

				<div className="col-span-12 sm:col-span-5">
					<TextInput
						label="City"
						name="city"
						required={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("city")}
					/>
				</div>

				<div className="col-span-12 sm:col-span-3">
					<TextInput
						label="State"
						name="state"
						required={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("state")}
					/>
				</div>

				<div className="col-span-12 sm:col-span-4">
					<TextInput
						label="Postal Code"
						name="zip"
						required={true}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("zip")}
					/>
				</div>
			</div>
		</>
	);
}
