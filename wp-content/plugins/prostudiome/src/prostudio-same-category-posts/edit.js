import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { 
    PanelBody, 
    RangeControl, 
    ToggleControl, 
    TextControl 
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { Placeholder } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
    const { 
        numberOfPosts, 
        displayFeaturedImage, 
        displayExcerpt, 
        displayDate,
        title
    } = attributes;
    
    const blockProps = useBlockProps();

    return (
        <>
            <InspectorControls>
                <PanelBody title={__("Settings", "prostudiome")}>
                    <TextControl
                        label={__("Block Title", "prostudiome")}
                        value={title}
                        onChange={(value) => setAttributes({ title: value })}
                    />
                    <RangeControl
                        label={__("Number of posts to display", "prostudiome")}
                        value={numberOfPosts}
                        onChange={(value) => setAttributes({ numberOfPosts: value })}
                        min={1}
                        max={10}
                    />
                    <ToggleControl
                        label={__("Display Featured Image", "prostudiome")}
                        checked={displayFeaturedImage}
                        onChange={(value) => setAttributes({ displayFeaturedImage: value })}
                    />
                    <ToggleControl
                        label={__("Display Excerpt", "prostudiome")}
                        checked={displayExcerpt}
                        onChange={(value) => setAttributes({ displayExcerpt: value })}
                    />
                    <ToggleControl
                        label={__("Display Date", "prostudiome")}
                        checked={displayDate}
                        onChange={(value) => setAttributes({ displayDate: value })}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...blockProps}>
                <Placeholder
                    icon="category"
                    label={__("Same Category Posts", "prostudiome")}
                    instructions={__(
                        "This block will display posts from the same category as the current post. The actual posts will be shown on the frontend.",
                        "prostudiome"
                    )}
                >
                    <div>
                        <p>
                            {__("Settings configured:", "prostudiome")}
                        </p>
                        <ul style={{ textAlign: "left" }}>
                            <li>{__("Title:", "prostudiome")} {title}</li>
                            <li>{__("Number of posts:", "prostudiome")} {numberOfPosts}</li>
                            <li>
                                {__("Featured image:", "prostudiome")} 
                                {displayFeaturedImage ? __("Shown", "prostudiome") : __("Hidden", "prostudiome")}
                            </li>
                            <li>
                                {__("Excerpt:", "prostudiome")} 
                                {displayExcerpt ? __("Shown", "prostudiome") : __("Hidden", "prostudiome")}
                            </li>
                            <li>
                                {__("Date:", "prostudiome")} 
                                {displayDate ? __("Shown", "prostudiome") : __("Hidden", "prostudiome")}
                            </li>
                        </ul>
                    </div>
                </Placeholder>
            </div>
        </>
    );
}
