<?php

namespace Forme\CodeGen\Source\Models;

use Forme\Framework\Models\PostTypeInterface;

final class PostType implements PostTypeInterface
{
    /**
     * Registers the `cpt_placeholder` post type.
     */
    public function register(): void
    {
        register_post_type('cpt_placeholder', [
            'labels'                => [
                'name'                  => __('Cpt placeholders', 'YOUR-TEXTDOMAIN'),
                'singular_name'         => __('Cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'all_items'             => __('All Cpt placeholders', 'YOUR-TEXTDOMAIN'),
                'archives'              => __('Cpt placeholder Archives', 'YOUR-TEXTDOMAIN'),
                'attributes'            => __('Cpt placeholder Attributes', 'YOUR-TEXTDOMAIN'),
                'insert_into_item'      => __('Insert into cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'uploaded_to_this_item' => __('Uploaded to this cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'featured_image'        => _x('Featured Image', 'cpt_placeholder', 'YOUR-TEXTDOMAIN'),
                'set_featured_image'    => _x('Set featured image', 'cpt_placeholder', 'YOUR-TEXTDOMAIN'),
                'remove_featured_image' => _x('Remove featured image', 'cpt_placeholder', 'YOUR-TEXTDOMAIN'),
                'use_featured_image'    => _x('Use as featured image', 'cpt_placeholder', 'YOUR-TEXTDOMAIN'),
                'filter_items_list'     => __('Filter cpt placeholders list', 'YOUR-TEXTDOMAIN'),
                'items_list_navigation' => __('Cpt placeholders list navigation', 'YOUR-TEXTDOMAIN'),
                'items_list'            => __('Cpt placeholders list', 'YOUR-TEXTDOMAIN'),
                'new_item'              => __('New Cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'add_new'               => __('Add New', 'YOUR-TEXTDOMAIN'),
                'add_new_item'          => __('Add New Cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'edit_item'             => __('Edit Cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'view_item'             => __('View Cpt placeholder', 'YOUR-TEXTDOMAIN'),
                'view_items'            => __('View Cpt placeholders', 'YOUR-TEXTDOMAIN'),
                'search_items'          => __('Search cpt placeholders', 'YOUR-TEXTDOMAIN'),
                'not_found'             => __('No cpt placeholders found', 'YOUR-TEXTDOMAIN'),
                'not_found_in_trash'    => __('No cpt placeholders found in trash', 'YOUR-TEXTDOMAIN'),
                'parent_item_colon'     => __('Parent Cpt placeholder:', 'YOUR-TEXTDOMAIN'),
                'menu_name'             => __('Cpt placeholders', 'YOUR-TEXTDOMAIN'),
            ],
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'supports'              => ['title', 'editor'],
            'has_archive'           => true,
            'rewrite'               => true,
            'query_var'             => true,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-admin-post',
            'show_in_rest'          => true,
            'rest_base'             => 'cpt_placeholder',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ]);
    }

    /**
     * Sets the post updated messages for the `cptplaceholder` post type.
     *
     * @param mixed[] $messages post updated messages
     *
     * @return mixed[] messages for the `cptplaceholder` post type
     */
    public function updateMessages(array $messages): array
    {
        global $post;

        $permalink = get_permalink($post);

        $messages['cpt_placeholder'] = [
            0  => '', // Unused. Messages start at index 1.
            /* translators: %s: post permalink */
            1  => sprintf(__('Cpt placeholder updated. <a target="_blank" href="%s">View cpt placeholder</a>', 'YOUR-TEXTDOMAIN'), esc_url($permalink)),
            2  => __('Custom field updated.', 'YOUR-TEXTDOMAIN'),
            3  => __('Custom field deleted.', 'YOUR-TEXTDOMAIN'),
            4  => __('Cpt placeholder updated.', 'YOUR-TEXTDOMAIN'),
            /* translators: %s: date and time of the revision */
            5  => isset($_GET['revision']) ? sprintf(__('Cpt placeholder restored to revision from %s', 'YOUR-TEXTDOMAIN'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            /* translators: %s: post permalink */
            6  => sprintf(__('Cpt placeholder published. <a href="%s">View cpt placeholder</a>', 'YOUR-TEXTDOMAIN'), esc_url($permalink)),
            7  => __('Cpt placeholder saved.', 'YOUR-TEXTDOMAIN'),
            /* translators: %s: post permalink */
            8  => sprintf(__('Cpt placeholder submitted. <a target="_blank" href="%s">Preview cpt placeholder</a>', 'YOUR-TEXTDOMAIN'), esc_url(add_query_arg('preview', 'true', $permalink))),
            /* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
            9  => sprintf(__('Cpt placeholder scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview cpt placeholder</a>', 'YOUR-TEXTDOMAIN'),
                date_i18n(__('M j, Y @ G:i', 'YOUR-TEXTDOMAIN'), strtotime($post->post_date)), esc_url($permalink)),
            /* translators: %s: post permalink */
            10 => sprintf(__('Cpt placeholder draft updated. <a target="_blank" href="%s">Preview cpt placeholder</a>', 'YOUR-TEXTDOMAIN'), esc_url(add_query_arg('preview', 'true', $permalink))),
        ];

        return $messages;
    }
}
