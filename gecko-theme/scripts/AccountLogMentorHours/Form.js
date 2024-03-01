import React, { useState } from "react";
import apiFetch from "@wordpress/api-fetch";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default function Form(props) {
	const { name, state, activityOptions } = props;

	const defaultStartTime = "08:00";
	const defaultEndTime = "17:00";

	const [isLoading, setIsLoading] = useState(false);
	const [mentorName, setMentorName] = useState(name ?? "");
	const [activityDate, setActivityDate] = useState("");
	const [startTime, setStartTime] = useState(defaultStartTime);
	const [endTime, setEndTime] = useState(defaultEndTime);
	const [hours, setHours] = useState(0);
	const [milesDriven, setMilesDriven] = useState(0);
	const [activityName, setActivityName] = useState("");
	const [activityState, setActivityState] = useState(state ?? "");
	const [agreeToTerms, setAgreeToTerms] = useState(false);

	const resetFields = () => {
		setActivityDate("");
		setStartTime("");
		setEndTime("");
		setHours(0);
		setMilesDriven(0);
		setActivityName("");
	};

	const handleSubmit = (e) => {
		e.preventDefault();

		if (!isLoading && !buttonDisabled) {
			setIsLoading(true);

			apiFetch({
				path: "learn-hunting/v1/log-hours",
				method: "post",
				data: {
					mentorName,
					activityDate,
					startTime,
					endTime,
					hours,
					milesDriven,
					activityName,
					activityState,
				},
			})
				.then((data) => {
					setIsLoading(false);

					resetFields();

					toast.success("Hours logged successfully");
				})
				.catch((error) => {
					console.warn(error);
					setIsLoading(false);
				});
		}
	};

	const buttonDisabled =
		!mentorName ||
		!activityName ||
		!activityDate ||
		!startTime ||
		!endTime ||
		hours <= 0 ||
		milesDriven < 0 ||
		!activityState ||
		!agreeToTerms ||
		isLoading;

	return (
		<form onSubmit={handleSubmit}>
			<ToastContainer
				position="bottom-right"
				autoClose={2000}
				hideProgressBar={true}
			/>

			<div className="mb-4 flex flex-col gap-4 md:flex-row md:gap-8">
				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="mentor-name">
						Mentor Name
					</label>

					<input
						type="text"
						id="mentor-name"
						name="mentor-name"
						className="block w-full"
						value={mentorName}
						onChange={(e) => setMentorName(e.target.value)}
					/>
				</div>

				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="activity_name">
						Activity Name
					</label>

					{activityOptions && activityOptions.length > 0 ? (
						<select
							className="block w-full"
							value={activityName}
							onChange={(e) => setActivityName(e.target.value)}
						>
							<option value="" selected={true}>
								- Select Activity -
							</option>

							{activityOptions.map((option, optionIndex) => (
								<option key={optionIndex} value={option}>
									{option}
								</option>
							))}
						</select>
					) : (
						<input
							type="text"
							id="activity_name"
							name="activity_name"
							className="block w-full"
							value={activityName}
							onChange={(e) => setActivityName(e.target.value)}
						/>
					)}
				</div>

				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="activity_name">
						Where did you instruct?
					</label>

					<select
						className="block w-full"
						value={activityState}
						onChange={(e) => setActivityState(e.target.value)}
					>
						<option value="AL">Alabama</option>
						<option value="AK">Alaska</option>
						<option value="AZ">Arizona</option>
						<option value="AR">Arkansas</option>
						<option value="CA">California</option>
						<option value="CO">Colorado</option>
						<option value="CT">Connecticut</option>
						<option value="DE">Delaware</option>
						<option value="DC">District Of Columbia</option>
						<option value="FL">Florida</option>
						<option value="GA">Georgia</option>
						<option value="HI">Hawaii</option>
						<option value="ID">Idaho</option>
						<option value="IL">Illinois</option>
						<option value="IN">Indiana</option>
						<option value="IA">Iowa</option>
						<option value="KS">Kansas</option>
						<option value="KY">Kentucky</option>
						<option value="LA">Louisiana</option>
						<option value="ME">Maine</option>
						<option value="MD">Maryland</option>
						<option value="MA">Massachusetts</option>
						<option value="MI">Michigan</option>
						<option value="MN">Minnesota</option>
						<option value="MS">Mississippi</option>
						<option value="MO">Missouri</option>
						<option value="MT">Montana</option>
						<option value="NE">Nebraska</option>
						<option value="NV">Nevada</option>
						<option value="NH">New Hampshire</option>
						<option value="NJ">New Jersey</option>
						<option value="NM">New Mexico</option>
						<option value="NY">New York</option>
						<option value="NC">North Carolina</option>
						<option value="ND">North Dakota</option>
						<option value="OH">Ohio</option>
						<option value="OK">Oklahoma</option>
						<option value="OR">Oregon</option>
						<option value="PA">Pennsylvania</option>
						<option value="RI">Rhode Island</option>
						<option value="SC">South Carolina</option>
						<option value="SD">South Dakota</option>
						<option value="TN">Tennessee</option>
						<option value="TX">Texas</option>
						<option value="UT">Utah</option>
						<option value="VT">Vermont</option>
						<option value="VA">Virginia</option>
						<option value="WA">Washington</option>
						<option value="WV">West Virginia</option>
						<option value="WI">Wisconsin</option>
						<option value="WY">Wyoming</option>
					</select>
				</div>
			</div>

			<div className="mb-4 flex flex-col gap-4 md:flex-row md:gap-8">
				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="hours">
						Total Hours
					</label>

					<input
						type="number"
						id="hours"
						name="hours"
						className="block w-full"
						value={hours}
						onChange={(e) => setHours(e.target.value)}
						min="0"
					/>
				</div>

				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="miles_driven">
						Miles Driven
					</label>

					<input
						type="number"
						id="miles_driven"
						name="miles_driven"
						className="block w-full"
						value={milesDriven}
						onChange={(e) => setMilesDriven(e.target.value)}
						min="0"
					/>
				</div>
			</div>

			<div className="mb-4 flex flex-col gap-4 md:flex-row md:gap-8">
				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="date">
						Date
					</label>

					<input
						type="date"
						id="date"
						name="date"
						className="block w-full"
						value={activityDate}
						onChange={(e) => setActivityDate(e.target.value)}
					/>
				</div>

				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="start_time">
						Start Time
					</label>

					<input
						type="time"
						id="start_time"
						name="start_time"
						className="block w-full"
						value={startTime}
						onChange={(e) => setStartTime(e.target.value)}
					/>
				</div>

				<div className="mb-2 grow">
					<label className="mb-1 block" htmlFor="end_time">
						End Time
					</label>

					<input
						type="time"
						id="end_time"
						name="end_time"
						className="block w-full"
						value={endTime}
						onChange={(e) => setEndTime(e.target.value)}
					/>
				</div>
			</div>

			<div className="mb-8">
				<label className="flex items-start gap-3">
					<input
						type="checkbox"
						id="agree_to_terms"
						name="agree_to_terms"
						className="mt-2 block h-4 w-4"
						checked={agreeToTerms}
						onChange={(e) => setAgreeToTerms(e.target.checked)}
					/>

					<span>
						I certify that the information provided is accurate and a true
						representation of my volunteer hours and activity.
					</span>
				</label>
			</div>

			<div className="mt-4">
				<button className="button" type="submit" disabled={buttonDisabled}>
					Submit
				</button>
			</div>
		</form>
	);
}
