import React, { useState, useEffect } from "react";

import TextInput from "./TextInput";

const defaultGameTypeOptions = [
	{
		value: "deer",
		label: "Deer",
	},
	{
		value: "turkey",
		label: "Turkey",
	},
	{
		value: "waterfowl",
		label: "Waterfowl (goose, duck, etc.)",
	},
	{
		value: "small_game",
		label: "Small Game (squirrel, rabbit, etc.)",
	},
	{
		value: "elk",
		label: "Elk",
	},
	{
		value: "upland_bird",
		label: "Upland Bird (pheasant, grouse, etc.)",
	},
	{
		value: "bear",
		label: "Bear",
	},
	{
		value: "hog",
		label: "Hog",
	},
	{
		value: "antelope",
		label: "Antelope",
	},
	{
		value: "predators",
		label: "Predators (coyote, wolf, etc.)",
	},
	{
		value: "moose",
		label: "Moose",
	},
	{
		value: "sheep_or_goat",
		label: "Sheep or Goat",
	},
	{
		value: "alligator",
		label: "Alligator",
	},
];

const defaultHuntingTypeOptions = [
	{
		value: "rifle",
		label: "Rifle",
	},
	{
		value: "shotgun",
		label: "Shotgun",
	},
	{
		value: "bow",
		label: "Bow",
	},
	{
		value: "crossbow",
		label: "Crossbow",
	},
	{
		value: "muzzleloader",
		label: "Muzzleloader",
	},
	{
		value: "trapping",
		label: "Trapping",
	},
];

const defaultInstructorTypeOptions = [
	{
		value: "state",
		label: "State Certified",
	},
	{
		value: "other",
		label: "Other Volunteer",
	},
];

export default function MentorFields(props) {
	const {
		errors,
		isSaving,
		profileDetails,
		setProfileDetails,
		handleInputChange,
		gameTypes,
		huntingTypes,
		instructorTypes,
	} = props;

	const gameTypeOptions = [];
	const huntingTypeOptions = [];
	const instructorTypeOptions = [];

	if (gameTypes) {
		gameTypes.forEach((gType) => {
			gameTypeOptions.push({
				value: gType.key,
				label: gType.value,
			});
		});
	} else {
		gameTypeOptions.push(...defaultGameTypeOptions);
	}

	if (huntingTypes) {
		huntingTypes.forEach((hType) => {
			huntingTypeOptions.push({
				value: hType.key,
				label: hType.value,
			});
		});
	} else {
		huntingTypeOptions.push(...defaultHuntingTypeOptions);
	}

	if (instructorTypes) {
		instructorTypes.forEach((iType) => {
			instructorTypeOptions.push({
				value: iType.key,
				label: iType.value,
			});
		});
	} else {
		instructorTypeOptions.push(...defaultInstructorTypeOptions);
	}

	const [selectedHuntingTypes, setSelectedHuntingTypes] = useState([]);
	const [selectedGameTypes, setSelectedGameTypes] = useState([]);
	const [selectedInstructorTypes, setSelectedInstructorTypes] = useState([]);

	const fieldHasError = (name) => {
		return errors && Object.keys(errors).includes(name);
	};

	useEffect(() => {
		if (profileDetails.huntingType) {
			const types = profileDetails.huntingType.split(",");
			setSelectedHuntingTypes([...types]);
		} else {
			setSelectedHuntingTypes([]);
		}

		if (profileDetails.gameType) {
			const types = profileDetails.gameType.split(",");
			setSelectedGameTypes([...types]);
		} else {
			setSelectedGameTypes([]);
		}

		if (profileDetails.instructorType) {
			const types = profileDetails.instructorType.split(",");
			setSelectedInstructorTypes([...types]);
		} else {
			setSelectedInstructorTypes([]);
		}
	}, [profileDetails]);

	const updateHuntingType = (value) => {
		const newProfileDetails = { ...profileDetails };
		const types = newProfileDetails.huntingType.split(",");

		if (types.includes(value)) {
			types.splice(types.indexOf(value), 1);

			newProfileDetails.huntingType = types.join(",");
		} else {
			newProfileDetails.huntingType = [...types, value].join(",");
		}

		setProfileDetails(newProfileDetails);
	};

	const updateGameType = (value) => {
		const newProfileDetails = { ...profileDetails };
		const types = newProfileDetails.gameType.split(",");

		if (types.includes(value)) {
			types.splice(types.indexOf(value), 1);

			newProfileDetails.gameType = types.join(",");
		} else {
			newProfileDetails.gameType = [...types, value].join(",");
		}

		setProfileDetails(newProfileDetails);
	};

	const updateInstructorType = (value) => {
		const newProfileDetails = { ...profileDetails };
		const types = newProfileDetails.instructorType.split(",");

		if (types.includes(value)) {
			types.splice(types.indexOf(value), 1);

			newProfileDetails.instructorType = types.join(",");
		} else {
			newProfileDetails.instructorType = [...types, value].join(",");
		}

		setProfileDetails(newProfileDetails);
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

				<div className="col-span-12 sm:col-span-4">
					<TextInput
						label="First Hunt Age"
						name="firstHuntAge"
						profileDetails={profileDetails}
						handleInputChange={handleInputChange}
						isSaving={isSaving}
						hasError={fieldHasError("firstHuntAge")}
					/>
				</div>

				<div className="col-span-12 sm:col-span-4"></div>

				<div className="col-span-12 sm:col-span-6">
					<strong className="mb-1 block">Hunting Types:</strong>

					{huntingTypeOptions.map((huntingType, index) => {
						return (
							<label
								className="mb-1 flex items-center gap-2"
								key={`hunting-type-${index}`}
							>
								<input
									type="checkbox"
									value={huntingType.value}
									disabled={isSaving}
									checked={selectedHuntingTypes.includes(
										huntingType.value
									)}
									onChange={(e) => {
										updateHuntingType(e.target.value);
									}}
								/>
								<span>{huntingType.label}</span>
							</label>
						);
					})}

					<strong className="mt-8 mb-1 block">Instructor Type:</strong>

					{instructorTypeOptions.map((instructorType, index) => {
						return (
							<label
								className="mb-1 flex items-center gap-2"
								key={`hunting-type-${index}`}
							>
								<input
									type="checkbox"
									value={instructorType.value}
									disabled={isSaving}
									checked={selectedInstructorTypes.includes(
										instructorType.value
									)}
									onChange={(e) => {
										updateInstructorType(e.target.value);
									}}
								/>
								<span>{instructorType.label}</span>
							</label>
						);
					})}
				</div>

				<div className="col-span-12 sm:col-span-6">
					<strong className="mb-1 block">Game Types:</strong>

					{gameTypeOptions.map((gameType, index) => {
						return (
							<label
								className="mb-1 flex items-center gap-2"
								key={`game-type-${index}`}
							>
								<input
									type="checkbox"
									value={gameType.value}
									checked={selectedGameTypes.includes(gameType.value)}
									disabled={isSaving}
									onChange={(e) => {
										updateGameType(e.target.value);
									}}
								/>
								<span>{gameType.label}</span>
							</label>
						);
					})}
				</div>
			</div>
		</>
	);
}
