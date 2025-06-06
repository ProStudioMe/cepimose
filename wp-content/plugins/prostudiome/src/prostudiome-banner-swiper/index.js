import { registerBlockType } from "@wordpress/blocks";
import "./style.scss";
import "./editor.scss";
import Edit from "./edit";
import metadata from "./block.json";

registerBlockType(metadata.name, {
	...metadata,
	edit: Edit,
	save: () => null, // Dynamic block, render.php handles the frontend
});
