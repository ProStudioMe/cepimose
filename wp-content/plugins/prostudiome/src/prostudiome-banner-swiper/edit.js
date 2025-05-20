import { useBlockProps } from "@wordpress/block-editor";
import { Placeholder } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useSelect } from "@wordpress/data";
import { store as coreStore } from "@wordpress/core-data";

export default function Edit() {
	const blockProps = useBlockProps();

	const banners = useSelect((select) => {
		return select(coreStore).getEntityRecords("postType", "post", {
			per_page: -1,
			categories: [
				select(coreStore).getEntityRecords("taxonomy", "category", {
					slug: ["banner-front-page"],
				})?.[0]?.id,
			],
			_fields: [
				"id",
				"title",
				"acf.banner_-_main_heading",
				"acf.banner_main_image",
				"acf.banner_subheading",
				"acf.banner_text",
				"acf.banner_link",
			],
		});
	}, []);

	if (!banners) {
		return (
			<div {...blockProps}>
				<Placeholder
					icon="format-gallery"
					label={__("Banner Slider", "prostudiome")}
					instructions={__("Loading banners...", "prostudiome")}
				/>
			</div>
		);
	}

	if (banners.length === 0) {
		return (
			<div {...blockProps}>
				<Placeholder
					icon="format-gallery"
					label={__("Banner Slider", "prostudiome")}
					instructions={__(
						"No banners found in the banner-front-page category.",
						"prostudiome",
					)}
				/>
			</div>
		);
	}

	return (
		<div {...blockProps}>
			<Placeholder
				icon="format-gallery"
				label={__("Banner Slider", "prostudiome")}
				instructions={__(
					"Banner slider will be displayed here on the frontend.",
					"prostudiome",
				)}
			/>
		</div>
	);
}
