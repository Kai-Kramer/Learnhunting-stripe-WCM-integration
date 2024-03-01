import React, { useState, useEffect } from "react";
import apiFetch from "@wordpress/api-fetch";

export default function App(props) {
	const [isLoading, setIsLoading] = useState(false);
	const [items, setItems] = useState([]);

	const getItems = () => {
		if (!isLoading) {
			setIsLoading(true);

			apiFetch({
				path: `gecko-theme/v1/example-react-block/items`,
				method: "GET",
			})
				.then((data) => {
					setIsLoading(false);

					if (data.items) {
						setItems(data.items);
					}
				})
				.catch((error) => {
					console.warn(error);
					setIsLoading(false);
				});
		}
	};

	useEffect(() => {
		getItems();
	}, []);

	return (
		<>
			<h1>Example React Block</h1>

			{isLoading && <p>Loading items...</p>}

			{items && (
				<ul>
					{items.map((item, itemIndex) => (
						<li key={itemIndex}>{item}</li>
					))}
				</ul>
			)}

			{!items && <p>No items</p>}
		</>
	);
}
