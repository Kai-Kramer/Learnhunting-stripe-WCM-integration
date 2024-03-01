import React, { useState } from "react";
import apiFetch from "@wordpress/api-fetch";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

import StandardFields from "./StandardFields";
import MentorFields from "./MentorFields";
import StudentFields from "./StudentFields";

export default function Details(props) {
	const {
		isSaving,
		setIsSaving,
		getInitialValues,
		memberType,
		profileDetails,
		setProfileDetails,
		userId,
		gameTypes,
		huntingTypes,
		instructorTypes,
	} = props;

	const [errors, setErrors] = useState(null);

	const handleSaveDetails = (e) => {
		e.preventDefault();

		if (!isSaving) {
			setIsSaving(true);

			const postDetails = { ...profileDetails };

			if (postDetails.email) {
				delete postDetails.email;
			}

			apiFetch({
				path: `learn-hunting/v1/edit-profile/${userId}`,
				method: "post",
				data: postDetails,
			})
				.then((data) => {
					setIsSaving(false);

					if (data.errors) {
						setErrors(data.errors);

						toast.error("There was an error saving your profile details");
					} else {
						toast.success("Profile details updated successfully");

						setErrors(null);
						getInitialValues();
					}
				})
				.catch((error) => {
					console.warn(error);
					setIsSaving(false);
				});
		}
	};

	const handleInputChange = (e) => {
		const { name, value } = e.target;
		const newDetails = { ...profileDetails };

		newDetails[name] = value;

		setProfileDetails(newDetails);
	};

	return (
		<>
			<ToastContainer
				position="bottom-right"
				autoClose={2000}
				hideProgressBar={true}
			/>

			{errors && (
				<div className="alert alert-danger">
					{Object.keys(errors).map((key, errIndex) => (
						<p className="text-red-700" key={errIndex}>
							{errors[key]}
						</p>
					))}
				</div>
			)}

			<form onSubmit={handleSaveDetails}>
				<StandardFields
					errors={errors}
					isSaving={isSaving}
					profileDetails={profileDetails}
					handleInputChange={handleInputChange}
				/>

				{memberType === "mentor" && (
					<MentorFields
						errors={errors}
						isSaving={isSaving}
						profileDetails={profileDetails}
						setProfileDetails={setProfileDetails}
						handleInputChange={handleInputChange}
						gameTypes={gameTypes}
						huntingTypes={huntingTypes}
						instructorTypes={instructorTypes}
					/>
				)}

				{memberType === "student" && (
					<StudentFields
						errors={errors}
						isSaving={isSaving}
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
					/>
				)}

				<div className="mt-4">
					<button className="button" type="submit" disabled={isSaving}>
						Save
					</button>
				</div>
			</form>
		</>
	);
}
