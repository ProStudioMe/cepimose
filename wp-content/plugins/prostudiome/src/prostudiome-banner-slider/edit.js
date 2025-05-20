/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from "@wordpress/block-editor";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * WordPress dependencies
 */
import { Placeholder } from "@wordpress/components";
import { megaphone } from "@wordpress/icons";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<Placeholder
				icon={megaphone}
				label={__("Banner Slider", "prostudiome")}
				instructions={__(
					"This block displays a slider with banners from posts in the banner-front-page category. The content will be visible on the frontend.",
					"prostudiome",
				)}
			>
				<div className="banner-preview-message">
					{__("Make sure you have:", "prostudiome")}
					<ul>
						<li>
							{__('• Posts in the "banner-front-page" category', "prostudiome")}
						</li>
						<li>
							{__("• ACF fields set up for those posts:", "prostudiome")}
							<ul>
								<li>{__("- banner_-_main_heading", "prostudiome")}</li>
								<li>{__("- banner_main_image", "prostudiome")}</li>
								<li>{__("- banner_subheading", "prostudiome")}</li>
								<li>{__("- banner_text", "prostudiome")}</li>
								<li>{__("- banner_link", "prostudiome")}</li>
							</ul>
						</li>
					</ul>
				</div>
			</Placeholder>
		</div>
	);
}
