import { registerBlockType } from "@wordpress/blocks";
import "./style.scss";
import "./editor.scss";

registerBlockType("prostudiome/ticker", {
	edit: () => {
		return "Ticker will be rendered on the front end.";
	},
});

// Enqueue frontend JS for ticker animation
document.addEventListener("DOMContentLoaded", () => {
	import("./frontend.js");
});
