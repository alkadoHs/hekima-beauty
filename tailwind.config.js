/** @type {import('tailwindcss').Config} */
module.exports = {
	content: ["./application/views/**/*.php", "./node_modules/flowbite/**/*.js"],
	theme: {
		extend: {},
	},
	plugins: [
		require("flowbite/plugin")({
			charts: true,
		}),
	],
};
