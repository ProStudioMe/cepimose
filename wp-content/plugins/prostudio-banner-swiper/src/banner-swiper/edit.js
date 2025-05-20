/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { Placeholder } from "@wordpress/components";
import { megaphone } from "@wordpress/icons";

/**
 * Editor styles
 */
import "./editor.scss";

export default function Edit() {
  const blockProps = useBlockProps();

  return (
    <div {...blockProps}>
      <Placeholder
        icon={megaphone}
        label={__("Banner Swiper", "prostudio-banner-swiper")}
        instructions={__(
          "This block displays a slider with banners from posts in the banner-front-page category. The content will be visible on the frontend.",
          "prostudio-banner-swiper"
        )}
      >
        <div className="banner-preview-message">
          {__("Make sure you have:", "prostudio-banner-swiper")}
          <ul>
            <li>
              {__(
                '• Posts in the "banner-front-page" category',
                "prostudio-banner-swiper"
              )}
            </li>
            <li>
              {__(
                "• ACF fields set up for those posts:",
                "prostudio-banner-swiper"
              )}
              <ul>
                <li>
                  {__("- banner_-_main_heading", "prostudio-banner-swiper")}
                </li>
                <li>{__("- banner_main_image", "prostudio-banner-swiper")}</li>
                <li>{__("- banner_subheading", "prostudio-banner-swiper")}</li>
                <li>{__("- banner_text", "prostudio-banner-swiper")}</li>
                <li>{__("- banner_link", "prostudio-banner-swiper")}</li>
              </ul>
            </li>
          </ul>
        </div>
      </Placeholder>
    </div>
  );
}
