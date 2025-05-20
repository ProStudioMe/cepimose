/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import Edit from "./edit";
import metadata from "./block.json";
import "./style.scss";

/**
 * Register the block
 */
registerBlockType(metadata.name, {
  edit: Edit,
  save: () => null, // Dynamic block uses PHP render
});
