<?php

/**
 * ZoloBlocks Loader.
 * @package Zolo
 */

namespace ZoloPro\Extensions;

use ZoloPro\Helpers\ZoloProHelpers;
use ZoloPro\Traits\SingletonTrait;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class DynamicContent
{
    use SingletonTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        // render block
        if (ZoloProHelpers::is_extension_enabled('dynamic-content')) {

            if (!is_admin()) {
                add_filter('render_block', [$this, 'add_attributes_in_block'], 2, 2);
                add_filter('render_block', [$this, 'zolo_dynamic_content_markup'], 99999, 3);
            }else{
                add_action("enqueue_block_assets", [$this, "enqueue_dynamic_content_editor_assets"]);
            }
        }
    }

    public function enqueue_dynamic_content_editor_assets()
    {
        $editor_assets_path = ZOLO_PRO_DIR_PATH . "build/extensions/dynamic-content/index.asset.php";
        if (file_exists($editor_assets_path)) {
			$editor_assets = include $editor_assets_path;
			wp_register_script(
				'zolo-dynamic-content-editor-script',
				ZOLO_PRO_ADMIN_URL . 'build/extensions/dynamic-content/index.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
            wp_register_style(
                'zolo-dynamic-content-editor-style',
                ZOLO_PRO_ADMIN_URL . 'build/extensions/dynamic-content/style-index.css',
                [],
                $editor_assets['version'],
                'all'
            );
            wp_enqueue_script('zolo-dynamic-content-editor-script');
            wp_enqueue_style('zolo-dynamic-content-editor-style');
		}
    }

    public function add_attributes_in_block($block_content, $parsed_block)
    {
        if (isset($parsed_block['blockName']) && str_contains($parsed_block['blockName'], 'zolo')) {
            $data = [
                'post_id' => get_the_ID(),
                'post_type' => get_post_type()
            ];
            $tags = new \WP_HTML_Tag_Processor($block_content);
            $tags->next_tag();
            $tags->set_attribute('data-dynamic-content', json_encode($data));
            $tags->add_class('zolo-block-wrapper');
            $block_content = $tags->get_updated_html();
        }
        return $block_content;
    }

    public function zolo_dynamic_content_markup($block_content, $parsed_block, $instance)
    {
        if (isset($parsed_block['blockName']) && str_contains($parsed_block['blockName'], 'zolo')) {
            $encoding = mb_detect_encoding($block_content, 'UTF-8, ISO-8859-1, ISO-8859-15', true);

            // Convert the content to UTF-8
            if ($encoding != 'UTF-8') {
                $block_content = mb_convert_encoding($block_content, 'UTF-8', 'auto');
            }

            libxml_use_internal_errors(true); // suppress errors

            // Load the HTML content into DOMDocument
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $block_content); // Corrected encoding
            libxml_clear_errors(); // Clear any libxml errors that have been suppressed

            $xpath = new \DOMXPath($dom);
            $modified_block_content = $this->dynamic_content_text($xpath, $dom, $instance->context);

            $block_content = $modified_block_content;
        }
        return $block_content;
    }

    public function dynamic_content_text($xpath, $dom, $context)
    {
        $this->process_rich_text($xpath, $dom, $context);
        return $this->get_body_content($dom);
    }

    private function process_rich_text($xpath, $dom, $context)
    {
        $zdc_tags = $xpath->query("//zdc");
        foreach ($zdc_tags as $zdc_tag) {
            // Fetch the closest wrapper (first matching ancestor)
            $closest_wrapper = $xpath->query("ancestor::*[contains(@class, 'zolo-block-wrapper')]", $zdc_tag);

            if ($closest_wrapper->length > 0) {
                foreach ($closest_wrapper as $index => $closest_wrapper_item) {
                    $dynamic_data_attributes = $closest_wrapper_item->getAttribute('data-dynamic-content');
                    if (!empty($dynamic_data_attributes)) {
                        $dynamic_data = json_decode($dynamic_data_attributes, true);
                        $this->process_dynamic_text($zdc_tag, $dom, $dynamic_data, 'attributes', $context);
                    }
                }
            }
        }
    }

    private function get_processed_text_data($tag, $sourceType)
    {
        if ($sourceType === 'attributes') {
            $attrs = [];
            foreach ($tag->attributes as $attr) {
                $attrs[$attr->name] = $attr->value;
            }
            return $attrs;
        }
    }

    private function get_dc_post_id($settigs, $dynamic_data)
    {
        $post_id = '';
        if (isset($settigs['postsource'])) {
            switch ($settigs['postsource']) {
                case 'current':
                    $post_id = $dynamic_data['post_id'] ?: get_the_ID();
                    break;
                case 'specific':
                    $post_id = isset($settigs['specificpost']) ? $settigs['specificpost'] : '';
                    break;
            }
        }

        return $post_id;
    }

    /**
     * Replace content in a string up to a specified character limit.
     *
     * @param string $content        The original content.
     * @param string $replacement    The replacement string.
     * @param int    $limit          The character limit for the final content.
     *
     * @return string The modified string with the replacement applied within the limit.
     */
    private function replace_content_with_limit($content, $replacement, $limit)
    {

        if(empty($limit)) {
            $limit = 0;
        }

        if(empty($content)) {
            return $content;
        }


        // If no limit or content length is within the limit, return the content as is
        if ($limit === 0 || strlen($content) <= $limit) {
            return $content;
        }

        // Truncate the content to the limit
        $truncatedContent = substr($content, 0, $limit);

        if (empty($replacement)) {
            return $truncatedContent;
        }else {
            return $truncatedContent . $replacement;
        }
    }


    private function get_dc_user($type, $id){
        $user = '';
        switch ($type) {
            case 'current':
                $user = wp_get_current_user();
                break;
            case 'specific':
                $user = get_user($id);
                break;
        }

        return $user;
    }

    private function process_dynamic_text($tag, $dom, $dynamic_data, $sourceType = 'attributes', $context = null)
    {
        $settigs = $this->get_processed_text_data($tag, $sourceType);
        $current_route = $settigs['currentroute'] ?? '';
        $post_id = $this->get_dc_post_id($settigs, $dynamic_data);
        $fallback = $settigs['fallback'] ?? '';
        $content = $fallback; // Default to fallback
        $character_limit = $settigs['characterlimit'] ?? 0;

        if (!empty($current_route)) {
            switch ($current_route) {
                case '/post-title':
                    $content = get_the_title($post_id);
                    break;

                case '/post-id':
                    $content = $post_id;
                    break;

                case '/post-slug':
                    $content = get_post_field('post_name', $post_id);
                    break;

                case '/post-date':
                    $date_type = $settigs['postdatetype'] ?? '';
                    $date_format = $settigs['postdateformat'] ?? '';

                    if ($date_format === 'custom') {
                        $date_format = $settigs['postdatecustomformat'] ?? '';
                    }

                    $content = $date_type === 'modified'
                        ? get_the_modified_date($date_format, $post_id)
                        : get_the_date($date_format, $post_id);
                    break;

                case '/post-time':
                    $time_type = $settigs['posttimetype'] ?? '';
                    $time_format = $settigs['posttimeformat'] ?? '';

                    if ($time_format === 'custom') {
                        $time_format = $settigs['posttimecustomformat'] ?? '';
                    }

                    $content = $time_type === 'modified'
                        ? get_the_modified_date($time_format, $post_id)
                        : get_the_date($time_format, $post_id);
                    break;

                case '/post-author':
                    $data_type = $settigs['authordatatype'] ?? '';
                    $author_id = get_post_field('post_author', $post_id);

                    switch ($data_type) {
                        case 'authorname':
                            $content = get_the_author_meta('display_name', $author_id);
                            break;
                        case 'authorbio':
                            $content = get_the_author_meta('description', $author_id);
                            break;
                        case 'authoremail':
                            $content = get_the_author_meta('user_email', $author_id);
                            break;
                        case 'authorfirstname':
                            $content = get_the_author_meta('first_name', $author_id);
                            break;
                        case 'authorlastname':
                            $content = get_the_author_meta('last_name', $author_id);
                            break;
                        case 'authornickname':
                            $content = get_the_author_meta('nickname', $author_id);
                            break;
                        case 'authormeta':
                            $meta_key = $settigs['authormetakey'] ?? '';
                            $content = get_the_author_meta($meta_key, $author_id);
                            break;
                    }
                    break;

                case '/post-comment':
                    $data_type = $settigs['commentdatatype'] ?? '';

                    if ($data_type === 'commentcount') {
                        $content = (string)get_comments_number($post_id); // No fallback here, show 0
                    } elseif ($data_type === 'commentnumbers') {
                        $post_comment_count = (int)get_comments_number($post_id);
                        $no_comments = $settigs['nocomment'] ?? '';
                        $singular = $settigs['singlecomment'] ?? '';
                        $multiple = $settigs['multiplecomment'] ?? '';

                        if ($post_comment_count === 1) {
                            $content = $singular;
                        } elseif ($post_comment_count > 1) {
                            $content = str_replace('{number}', $post_comment_count, $multiple);
                        } else {
                            $content = $no_comments;
                        }
                    }
                    break;

                case '/post-excerpt':
                    $content = get_the_excerpt($post_id);
                    break;
                case '/posttags':
                    $tag_index = isset($settigs['tagsindex']) ? $settigs['tagsindex'] : 0;
                    $tags = wp_get_post_tags($post_id);
                    if (isset($tags[$tag_index])) {
                        $content = $tags[$tag_index]->name;
                    }
                    break;
                case '/post-categories':
                    $category_index = isset($settigs['catiegoriesindex']) ? $settigs['catiegoriesindex'] : 0;
                    $categories = get_the_category($post_id);
                    if (isset($categories[$category_index])) {
                        $content = $categories[$category_index]->name;
                    }
                    break;
                case '/post-status':
                    $content = get_post_status($post_id);
                    break;
                case '/post-type':
                    $content = get_post_type($post_id);
                    break;
                case '/post-meta':
                    $meta_key = $settigs['postmetakey'] ?? $settigs['postcustommetakey'] ?? '';
                    if (!empty($meta_key)) {
                        $content = get_post_meta($post_id, $meta_key, true);
                    }
                    break;
                case '/post-featured-image':
                    $data_type = $settigs['postfeaturedimagedatatype'] ?? '';
                    $thumbnail_id = get_post_thumbnail_id( $post_id );
                    switch ($data_type) {
                        case 'alt':
                            $content = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                            break;
                        case 'caption':
                            $content = get_the_excerpt($thumbnail_id);
                            break;
                        case 'description':
                            $content = get_post_field('post_content', $thumbnail_id);
                            break;
                        case 'title':
                            $content = get_the_title($thumbnail_id);
                            break;
                    }
                    break;
                case '/site-title':
                    $content = get_bloginfo('name');
                    break;
                case '/site-tagline':
                    $content = get_bloginfo('description');
                    break;
                case '/site-date':
                    $date_format = get_option( 'date_format' );
                    if (!empty($settigs['sitedateformat'])) {
                        $date_format = $settigs['sitedateformat'];
                    }

                    if (!empty($settigs['sitedatecustomformat']) && $date_format === 'custom') {
                        $date_format = $settigs['sitedatecustomformat'];
                    }

                    $content = date_i18n($date_format);
                    break;
                case '/site-time':
                    $time_format = get_option( 'time_format' );
                    if (!empty($settigs['sitetimeformat'])) {
                        $time_format = $settigs['sitetimeformat'];
                    }

                    if (!empty($settigs['sitetimecustomformat']) && $time_format === 'custom') {
                        $time_format = $settigs['sitetimecustomformat'];
                    }
                    $content = date_i18n($time_format);
                    break;
                case '/archive-title':
                    $content = get_the_archive_title();
                    break;
                case '/archive-description':
                    $content = get_the_archive_description();
                    break;
                case '/terms-id':
                    $taxonomy = $settigs['termtaxonomy'] ?? '';
                    $term_id = $settigs['termsterm'] ?? '';
                    $term = get_term($term_id, $taxonomy);
                    if( !is_wp_error($term) && $term ) {
                        $content = $term->term_id;
                    }
                    break;
                case '/terms-title':
                    $taxonomy = $settigs['termtaxonomy'] ?? '';
                    $term_id = $settigs['termsterm'] ?? '';
                    $term = get_term($term_id, $taxonomy);
                    if( !is_wp_error($term) && $term ) {
                        $content = $term->name;
                    }
                    break;
                case '/terms-description':
                    $taxonomy = $settigs['termtaxonomy'] ?? '';
                    $term_id = $settigs['termsterm'] ?? '';
                    $term = get_term($term_id, $taxonomy);
                    if( !is_wp_error($term) && $term ) {
                        $content = $term->description;
                    }
                    break;
                case '/terms-slug':
                    $taxonomy = $settigs['termtaxonomy'] ?? '';
                    $term_id = $settigs['termsterm'] ?? '';
                    $term = get_term($term_id, $taxonomy);
                    if( !is_wp_error($term) && $term ) {
                        $content = $term->slug;
                    }
                    break;
                case '/terms-count':
                    $taxonomy = $settigs['termtaxonomy'] ?? '';
                    $term_id = $settigs['termsterm'] ?? '';
                    $term = get_term($term_id, $taxonomy);
                    if( !is_wp_error($term) && $term ) {
                        $content = $term->count;
                    }
                    break;
                case '/user-username':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->user_login;
                    break;
                case '/user-email':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->user_email;
                    break;
                case '/user-website':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->user_url;
                    break;
                case '/user-display-name':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->display_name;
                    break;
                case '/user-nickname':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->nickname;
                    break;
                case '/user-first-name':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->first_name;
                    break;
                case '/user-last-name':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->last_name;
                    break;
                case '/user-biographical-info':
                    $user_type = $settigs['usertype'] ?? '';
                    $user_id = $settigs['specificuser'] ?? '';
                    $user = $this->get_dc_user($user_type, $user_id);
                    $content = $user->description;
                    break;
                case '/search-query':
                    $content = get_search_query();
                    break;
                case '/search-result-count':
                    $content = $this->get_search_result_count();
                    break;
            }
        }

        // Use fallback if content is empty
        $content = $content ?: $fallback;

        // Append content to HTML only once
        $this->append_to_html($dom, $tag, $content, $character_limit);
    }

    public function get_search_result_count()
    {
        if( is_search() ) {
            global $wp_query;
            return $wp_query->found_posts;
        }
        return '';
    }


    public function convert_to_time_string($input)
    {
        // Check if the input is a string, number, or boolean
        if (is_string($input) || is_numeric($input) || is_bool($input)) {
            return strval($input); // Convert to string representation
        }

        // Check if the input is an array or an object
        if (is_array($input) || is_object($input)) {
            // Use var_export to convert arrays and objects to their string representations
            return var_export($input, true);
        }

        // If none of the above conditions are met, return the input as is
        // This could happen if the input is null or another unexpected type
        return $input;
    }

    public function append_to_html($dom, $tag, $htmlContent, $limit = 0)
    {
        if ($htmlContent !== null && $htmlContent !== '' && $htmlContent !== []) {
            $htmlContent = $this->convert_to_time_string($htmlContent);
            if ($this->is_html($htmlContent)) {
                // Create a DOMDocumentFragment that is associated with the provided DOMDocument
                $frag = $dom->createDocumentFragment();

                // Append the provided HTML content to the fragment
                // Ensure the content is valid XML (self-closing tags and proper structure)
                @$frag->appendXML($this->fix_self_closing_tags($htmlContent));

                // Clear the existing content of the tag
                $tag->nodeValue = "";

                // Append the fragment to the tag
                $tag->appendChild($frag);
            } else {
                $tag->nodeValue = $this->replace_content_with_limit($htmlContent, '', $limit);
            }
        }else{
            $tag->nodeValue = '';
        }
    }

    public function is_html($string)
    {
        // Strip all HTML tags using WordPress function
        $stripped_string = wp_strip_all_tags($string);

        // If the original string and stripped string are different, it's HTML
        return $string !== $stripped_string;
    }

    private function fix_self_closing_tags($html)
    {
        // List of self-closing tags in HTML
        $selfClosingTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

        foreach ($selfClosingTags as $tag) {
            // Regex to match improper or incomplete self-closing tags
            $pattern = '/<(' . $tag . ')([^>]*)>/i';

            // Callback to validate and correct the tags
            $html = preg_replace_callback($pattern, function ($matches) {
                $tag = $matches[1]; // The tag name
                $attributes = $matches[2]; // Any attributes inside the tag

                // Check if the tag is already properly self-closing
                if (preg_match('/\/\s*>$/', $matches[0])) {
                    return $matches[0]; // Already correct, return as-is
                }

                // Otherwise, fix the tag to be properly self-closing
                return "<{$tag}{$attributes} />";
            }, $html);
        }

        return $html;
    }

    private function get_body_content($dom)
    {
        $body = $dom->getElementsByTagName('body')->item(0);
        $innerHTML = '';

        if (!empty($body->childNodes)) {
            foreach ($body->childNodes as $child) {
                $innerHTML .= $dom->saveHTML($child);
            }
        }

        return trim($innerHTML);
    }
}
