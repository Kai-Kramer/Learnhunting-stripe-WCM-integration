import React, { useState, useEffect } from "react";
import apiFetch from "@wordpress/api-fetch";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default function AccountChangePassword(props) {
	const {} = props;

	const [isLoading, setIsLoading] = useState(false);
	const [isValidForm, setIsValidForm] = useState(false);
	const [errors, setErrors] = useState([]);

	const [oldPasswd, setOldPasswd] = useState("wk7wW*Eouj3DZg3LeId&NEsg");
	const [passwd1, setPasswd1] = useState("asdfasdf");
	const [passwd2, setPasswd2] = useState("asdfasdf");

	const updatePassword = (e) => {
		e.preventDefault();

		if (!isLoading) {
			setIsLoading(true);

			setErrors([]);

			apiFetch({
				path: `learn-hunting/v1/update-password`,
				method: "post",
				data: {
					oldPasswd,
					passwd1,
					passwd2,
				},
			})
				.then((data) => {
					setIsLoading(false);

					if (data.errors) {
						setErrors(data.errors);

						toast.error("There was an error updating your password");
					} else {
						setOldPasswd("");
						setPasswd1("");
						setPasswd2("");

						toast.success("Password updated successfully");
					}
				})
				.catch((error) => {
					setIsLoading(false);
					console.warn(error);
				});
		}
	};

	const checkFormValid = () => {
		let valid = true;

		if (!oldPasswd || !passwd1 || !passwd2) {
			valid = false;
		}
		if (passwd1 !== passwd2) {
			valid = false;
		}
		if (passwd1.length < 8) {
			valid = false;
		}
		if (passwd1.length > 40) {
			valid = false;
		}

		setIsValidForm(valid);
	};

	useEffect(() => {
		checkFormValid();
	}, [oldPasswd, passwd1, passwd2]);

	return (
		<>
			<ToastContainer
				position="bottom-right"
				autoClose={2000}
				hideProgressBar={true}
			/>

			<h2>Change Password</h2>

			{errors &&
				errors.length > 0 &&
				errors.map((error, errIndex) => (
					<div
						key={`error-${errIndex}`}
						className="font-bold text-red-500"
					>
						{error}
					</div>
				))}

			<form onSubmit={updatePassword}>
				<div className="mb-8">
					<label className="mb-1 block font-bold">Current Password:</label>
					<input
						type="password"
						className="m-0 block w-full"
						required={true}
						name="oldPasswd"
						value={oldPasswd}
						onChange={(e) => setOldPasswd(e.target.value)}
					/>
				</div>

				<div className="mb-8">
					<label className="mb-1 block font-bold">New Password:</label>
					<input
						type="password"
						className="m-0 block w-full"
						required={true}
						name="passwd1"
						value={passwd1}
						onChange={(e) => setPasswd1(e.target.value)}
					/>
				</div>

				<div className="mb-8">
					<label className="mb-1 block font-bold">
						Repeat New Password:
					</label>
					<input
						type="password"
						className="m-0 block w-full"
						required={true}
						name="passwd2"
						value={passwd2}
						onChange={(e) => setPasswd2(e.target.value)}
					/>
				</div>

				<button
					type="submit"
					className="button"
					onClick={updatePassword}
					disabled={!isValidForm || isLoading}
				>
					Save
				</button>
			</form>
		</>
	);
}
