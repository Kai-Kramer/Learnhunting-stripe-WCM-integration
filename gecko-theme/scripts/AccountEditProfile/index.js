import React, { useState, useEffect } from "react";
import apiFetch from "@wordpress/api-fetch";

import Details from "./Details";
import Loading from "./Loading";
import Avatar from "./Avatar";

export default function AccountEditProfile(props) {
	const { game_types, hunting_types, instructor_types } = props;

	const [isLoading, setIsLoading] = useState(false);
	const [isSaving, setIsSaving] = useState(false);
	const [memberType, setMemberType] = useState("");
	const [profileDetails, setProfileDetails] = useState(null);
	const [avatarDetails, setAvatarDetails] = useState(null);

	useEffect(() => {
		getInitialValues();
	}, []);

	const getInitialValues = () => {
		if (!isLoading) {
			setIsLoading(true);

			apiFetch({
				path: `learn-hunting/v1/edit-profile`,
				method: "get",
			})
				.then((data) => {
					setIsLoading(false);

					setMemberType(data.memberType);

					setProfileDetails(data.profileDetails);

					setAvatarDetails(data.avatarDetails);
				})
				.catch((error) => {
					console.warn(error);
					setIsLoading(false);
				});
		}
	};

	return (
		<>
			<h2>Edit Profile</h2>

			{!isLoading && !isSaving && (
				<Avatar
					avatarDetails={avatarDetails}
					getInitialValues={getInitialValues}
				/>
			)}

			<div className="relative">
				<Loading active={isLoading || isSaving} />

				{!isLoading && !profileDetails && (
					<p>Error loading profile details</p>
				)}

				{profileDetails && (
					<Details
						isSaving={isSaving}
						setIsSaving={setIsSaving}
						getInitialValues={getInitialValues}
						memberType={memberType}
						profileDetails={profileDetails}
						setProfileDetails={setProfileDetails}
						gameTypes={game_types}
						huntingTypes={hunting_types}
						instructorTypes={instructor_types}
					/>
				)}
			</div>
		</>
	);
}
