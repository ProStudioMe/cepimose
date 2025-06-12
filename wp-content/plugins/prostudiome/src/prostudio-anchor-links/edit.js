import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, TextControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { Placeholder } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const { title, includeH1, includeH2, includeH3, showNumbers } = attributes;

	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Settings", "prostudiome")}>
					<TextControl
						label={__("Block Title", "prostudiome")}
						value={title}
						onChange={(value) => setAttributes({ title: value })}
						help={__("Leave empty to hide the title", "prostudiome")}
					/>
					<ToggleControl
						label={__("Show Numbers", "prostudiome")}
						checked={showNumbers}
						onChange={(value) => setAttributes({ showNumbers: value })}
						help={__(
							"Display numbered list instead of bullet points",
							"prostudiome",
						)}
					/>
				</PanelBody>
				<PanelBody title={__("Include Headings", "prostudiome")}>
					<ToggleControl
						label={__("Include H1", "prostudiome")}
						checked={includeH1}
						onChange={(value) => setAttributes({ includeH1: value })}
					/>
					<ToggleControl
						label={__("Include H2", "prostudiome")}
						checked={includeH2}
						onChange={(value) => setAttributes({ includeH2: value })}
					/>
					<ToggleControl
						label={__("Include H3", "prostudiome")}
						checked={includeH3}
						onChange={(value) => setAttributes({ includeH3: value })}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<Placeholder
					icon="admin-links"
					label={__("Anchor Links", "prostudiome")}
					instructions={__(
						"This block will display a table of contents with anchor links to all headings with IDs on the current page. The actual links will be shown on the frontend.",
						"prostudiome",
					)}
				>
					<div>
						<p>{__("Settings configured:", "prostudiome")}</p>
						<ul style={{ textAlign: "left" }}>
							<li>
								{__("Title:", "prostudiome")}{" "}
								{title || __("(No title)", "prostudiome")}
							</li>
							<li>
								{__("Include headings:", "prostudiome")}
								H1:{includeH1 ? "Yes" : "No"}, H2:{includeH2 ? "Yes" : "No"},
								H3:{includeH3 ? "Yes" : "No"}
							</li>
							<li>
								{__("Show numbers:", "prostudiome")}
								{showNumbers
									? __("Yes", "prostudiome")
									: __("No", "prostudiome")}
							</li>
						</ul>
					</div>
				</Placeholder>
			</div>
		</>
	);
}
