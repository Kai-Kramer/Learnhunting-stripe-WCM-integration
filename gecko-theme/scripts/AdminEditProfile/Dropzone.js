import React, { useState, useCallback } from "react";
import { useDropzone } from "react-dropzone";
import axios from "axios";

const readableBytes = (bytes) => {
	var i = Math.floor(Math.log(bytes) / Math.log(1024)),
		sizes = ["b", "kb", "mb", "gb", "tb", "pb", "eb", "zb", "yb"];

	return (bytes / Math.pow(1024, i)).toFixed(2) * 1 + "" + sizes[i];
};

export default function Dropzone(props) {
	const { setFormActive, getInitialValues, userId } = props;

	const className = "learnhunting-avatar-dropzone";
	const maxFilesize = 1100000; // 1.1mb

	const [selectedFile, setSelectedFile] = useState(null);
	const [isUploading, setIsUploading] = useState(false);

	const onDrop = useCallback((acceptedFiles) => {
		if (acceptedFiles[0]) {
			setSelectedFile(acceptedFiles[0]);
		}
	}, []);

	const uploadLogo = (file) => {
		const postUrl = `/wp-json/learn-hunting/v1/upload-avatar/${userId}`;

		setIsUploading(true);

		let formData = new FormData();
		formData.append("file", file);

		axios
			.post(postUrl, formData, {
				headers: {
					"X-WP-Nonce": window.lhApiSettings.nonce,
				},
			})
			.then((response) => {
				if (response.data.success) {
					setFormActive(false);
					getInitialValues();
				}

				if (response.data.error) {
					console.warn(response.data.error);
					setIsUploading(false);
					setSelectedFile(null);
				}
			})
			.catch((error) => {
				console.warn(error);
			});
	};

	const clearProfileImage = () => {
		const msg =
			"Are you sure you want to permanently delete your profile image?";

		if (confirm(msg) && !isUploading) {
			setIsUploading(true);

			axios
				.post(
					`/wp-json/learn-hunting/v1/clear-avatar/${userId}`,
					{},
					{
						headers: {
							"X-WP-Nonce": window.lhApiSettings.nonce,
						},
					}
				)
				.then((response) => {
					getInitialValues();
				})
				.catch((error) => {
					console.warn(error);

					setIsUploading(false);
				});
		}
	};

	const { getRootProps, getInputProps, isDragActive } = useDropzone({
		onDrop,
		accept: "image/*",
	});

	return (
		<div className={className}>
			<div
				{...getRootProps({
					className: className + "__dropzone",
					"data-active": isDragActive,
					"data-selected": selectedFile ? "true" : "false",
					"data-invalid-size":
						selectedFile && selectedFile.size > maxFilesize,
				})}
			>
				<input {...getInputProps()} />

				<div className={className + "__message"} data-active={isDragActive}>
					<strong>Click Here</strong> or Drag an image here to choose a new
					logo (max {readableBytes(maxFilesize)})
				</div>

				<div className={className + "__name"}>
					{selectedFile ? (
						<strong>{selectedFile.name}</strong>
					) : (
						"No file selected"
					)}{" "}
					{selectedFile ? " - " + readableBytes(selectedFile.size) : ""}
				</div>

				{selectedFile && selectedFile.size > maxFilesize && (
					<div className={className + "__error"}>
						Image too large! {readableBytes(maxFilesize)} is the limit,
						please choose a smaller image.
					</div>
				)}
			</div>

			<div className={className + "__actions"}>
				<button
					type="button"
					className="button"
					disabled={
						isUploading ||
						!selectedFile ||
						(selectedFile && selectedFile.size > maxFilesize)
					}
					onClick={(e) => {
						if (selectedFile) {
							uploadLogo(selectedFile);
						}
					}}
				>
					{isUploading ? "Uploading..." : "Save"}
				</button>

				<button
					type="button"
					className="button"
					onClick={(e) => {
						setSelectedFile(null);
						setFormActive(false);
					}}
				>
					Cancel
				</button>

				<button
					type="button"
					className="link-button appearance-none border-none"
					disabled={isUploading}
					onClick={(e) => {
						e.preventDefault();
						clearProfileImage();
					}}
				>
					Delete Profile Image
				</button>
			</div>
		</div>
	);
}
