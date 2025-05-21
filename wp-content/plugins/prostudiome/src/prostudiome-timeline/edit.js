import { useBlockProps } from "@wordpress/block-editor";
import { Placeholder } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useSelect } from "@wordpress/data";
import { store as coreStore } from "@wordpress/core-data";

export default function Edit() {
	const blockProps = useBlockProps();

	const timelineItems = useSelect((select) => {
		return select(coreStore).getEntityRecords("postType", "post", {
			per_page: -1,
			categories: [
				select(coreStore).getEntityRecords("taxonomy", "category", {
					slug: ["vaccination-timeline"],
				})?.[0]?.id,
			],
			_fields: [
				"id",
				"acf.vaccination_age",
				"acf.vaccination_image",
				"acf.vaccination_text",
			],
		});
	}, []);

	if (!timelineItems) {
		return (
			<div {...blockProps}>
				<Placeholder
					icon="clock"
					label={__("Timeline", "prostudiome")}
					instructions={__("Loading timeline items...", "prostudiome")}
				/>
			</div>
		);
	}

	if (timelineItems.length === 0) {
		return (
			<div {...blockProps}>
				<Placeholder
					icon="clock"
					label={__("Timeline", "prostudiome")}
					instructions={__(
						"No items found in the vaccination-timeline category.",
						"prostudiome",
					)}
				/>
			</div>
		);
	}

	return (
		<div {...blockProps}>
			<Placeholder
				icon="clock"
				label={__("Timeline", "prostudiome")}
				instructions={__(
					"Timeline will be displayed here on the frontend.",
					"prostudiome",
				)}
			/>
		</div>
	);
}
