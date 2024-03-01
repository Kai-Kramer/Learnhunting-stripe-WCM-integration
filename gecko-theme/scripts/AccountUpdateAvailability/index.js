import React, { useState, useEffect, useRef } from "react";
import apiFetch from "@wordpress/api-fetch";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default function AccountUpdateAvailability(props) {
	const {} = props;

	const [isLoading, setIsLoading] = useState(false);
	const [isAvailable, setIsAvailable] = useState(false);
	const [errors, setErrors] = useState(null);
	const [didInit, setDidInit] = useState(false);

	useEffect(() => {
		getAvailability();
	}, []);

	const getAvailability = () => {
		setIsLoading(true);

		apiFetch({
			path: `learn-hunting/v1/availability`,
			method: "get",
		})
			.then((data) => {
				setIsLoading(false);

				if (!didInit) {
					setDidInit(true);
				}

				if (data.errors) {
					setErrors(data.errors);
				} else {
					setIsAvailable(data.is_available);
				}
			})
			.catch((error) => {
				console.warn(error);
				setIsLoading(false);
			});
	};

	const handleToggle = (e) => {
		if (!isLoading) {
			setIsLoading(true);

			apiFetch({
				path: "learn-hunting/v1/availability",
				method: "post",
				data: {
					is_available: !isAvailable,
				},
			})
				.then((data) => {
					setIsLoading(false);

					if (data.errors) {
						setErrors(data.errors);
						toast.error("There was an error updating your availability");
					} else {
						setErrors(null);
						setIsAvailable(data.is_available);
						toast.success("Availability updated successfully");
					}
				})
				.catch((error) => {
					console.warn(error);
					setIsLoading(false);
				});
		}
	};

	return (
		<>
			<ToastContainer
				position="bottom-right"
				autoClose={2000}
				hideProgressBar={true}
			/>

			<h2>Update Availability</h2>

			<p>
				Toggle between being available to mentor others and taking some time
				for yourself. When your indicator is "available", you will show up
				on the Instructor Map for students to request help from you. When
				your indicator is turned to "off", you will be removed from the
				Instructor Map so that no one reaches out for help. You will still
				have access to all the other features of LearnHunting.org including
				access to your courses. We suggest toggling to the "off" position
				when you have a planned vacation or are just looking for time away
				for yourself.
			</p>

			{errors && (
				<div className="alert alert-danger">
					{Object.keys(errors).map((key, errIndex) => (
						<p className="text-red-700" key={errIndex}>
							{errors[key]}
						</p>
					))}
				</div>
			)}

			<div
				className="availability-toggle"
				data-did-init={didInit ? "true" : "false"}
				data-loading={isLoading ? "true" : "false"}
			>
				<div className="availability-toggle__loading">
					<span>Loading...</span>
				</div>

				<label className="availability-toggle__wrapper">
					<input
						type="checkbox"
						className="availability-toggle__checkbox peer"
						checked={isAvailable}
						onChange={handleToggle}
					/>

					<div className="availability-toggle__dot-wrapper">
						<div className="availability-toggle__dot-icon">
							<svg
								viewBox="0 0 512 512"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M440.63 126.366C451.79 136.662 451.79 154.681 440.63 164.977L220.876 384.634C210.575 395.789 192.548 395.789 182.247 384.634L72.3696 274.806C61.2101 264.509 61.2101 246.491 72.3696 236.194C82.6706 225.04 100.697 225.04 110.998 236.194L201.132 326.288L402.002 126.366C412.303 115.211 430.329 115.211 440.63 126.366Z"
									fill="black"
								/>
							</svg>
						</div>
					</div>

					<span className="availability-toggle__label">
						{isAvailable ? "Available" : "Unavailable"}
					</span>
				</label>
			</div>
		</>
	);
}
