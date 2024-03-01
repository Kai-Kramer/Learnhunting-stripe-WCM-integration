import React from "react";

import Form from "./Form";

export default function AccountLogMentorHours(props) {
	const { name, state, activityOptions } = props;

	return (
		<>
			<h2>Log Mentorship Hours</h2>

			<Form name={name} state={state} activityOptions={activityOptions} />

			<p>
				Here you will be able to log the hours that you dedicated to
				mentoring others. By completing and submitting this form, you allow
				your state to benefit from your time and gain a greater
				understanding of the educational opportunities you are providing new
				hunters. This form is only used for mentored experiences through
				LearnHunting.org and not necessary for your beginner hunter
				education courses through the state.
			</p>
		</>
	);
}
