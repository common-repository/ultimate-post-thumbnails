<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( !class_exists( 'TN_Cache' ) ) {

    class TN_Cache {

        function __construct() {
            add_action( 'save_post', array( &$this, 'flush_cache' ) );
            add_action( 'deleted_post', array( &$this, 'flush_cache' ) );
            add_action( 'switch_theme', array( &$this, 'flush_cache' ) );
        }

        function flush_cache() {
            wp_cache_delete( 'tn_category_list', 'addway' );
            wp_cache_delete( 'tn_tag_list', 'addway' );
            wp_cache_delete( 'tn_post_list', 'addway' );
            wp_cache_delete( 'tn_page_list', 'addway' );
        }

        /**
         * Get registered sidebars
         * [updated] remove cache processing, as it is not a time sensitive operation ( like database query )
         *
         * @return [type] [description]
         */
        function get_sidebar() {
            global $wp_registered_sidebars;

            $list = array();
            if ( is_array( $wp_registered_sidebars ) && !empty( $wp_registered_sidebars ) ) {
                foreach ( $wp_registered_sidebars as $sidebar ) {
                    $list[$sidebar['name']] = $sidebar['name'];
                }
            }

            return $list;
        }

        function get_fontawesome_icons() {
            return array( 'fa fa-glass', 'fa fa-music', 'fa fa-search', 'fa fa-envelope-o', 'fa fa-heart', 'fa fa-star', 'fa fa-star-o', 'fa fa-user', 'fa fa-film', 'fa fa-th-large', 'fa fa-th', 'fa fa-th-list', 'fa fa-check', 'fa fa-times', 'fa fa-search-plus', 'fa fa-search-minus', 'fa fa-power-off', 'fa fa-signal', 'fa fa-cog', 'fa fa-trash-o', 'fa fa-home', 'fa fa-file-o', 'fa fa-clock-o', 'fa fa-road', 'fa fa-download', 'fa fa-arrow-circle-o-down', 'fa fa-arrow-circle-o-up', 'fa fa-inbox', 'fa fa-play-circle-o', 'fa fa-repeat', 'fa fa-refresh', 'fa fa-list-alt', 'fa fa-lock', 'fa fa-flag', 'fa fa-headphones', 'fa fa-volume-off', 'fa fa-volume-down', 'fa fa-volume-up', 'fa fa-qrcode', 'fa fa-barcode', 'fa fa-tag', 'fa fa-tags', 'fa fa-book', 'fa fa-bookmark', 'fa fa-print', 'fa fa-camera', 'fa fa-font', 'fa fa-bold', 'fa fa-italic', 'fa fa-text-height', 'fa fa-text-width', 'fa fa-align-left', 'fa fa-align-center', 'fa fa-align-right', 'fa fa-align-justify', 'fa fa-list', 'fa fa-outdent', 'fa fa-indent', 'fa fa-video-camera', 'fa fa-picture-o', 'fa fa-pencil', 'fa fa-map-marker', 'fa fa-adjust', 'fa fa-tint', 'fa fa-pencil-square-o', 'fa fa-share-square-o', 'fa fa-check-square-o', 'fa fa-arrows', 'fa fa-step-backward', 'fa fa-fast-backward', 'fa fa-backward', 'fa fa-play', 'fa fa-pause', 'fa fa-stop', 'fa fa-forward', 'fa fa-fast-forward', 'fa fa-step-forward', 'fa fa-eject', 'fa fa-chevron-left', 'fa fa-chevron-right', 'fa fa-plus-circle', 'fa fa-minus-circle', 'fa fa-times-circle', 'fa fa-check-circle', 'fa fa-question-circle', 'fa fa-info-circle', 'fa fa-crosshairs', 'fa fa-times-circle-o', 'fa fa-check-circle-o', 'fa fa-ban', 'fa fa-arrow-left', 'fa fa-arrow-right', 'fa fa-arrow-up', 'fa fa-arrow-down', 'fa fa-share', 'fa fa-expand', 'fa fa-compress', 'fa fa-plus', 'fa fa-minus', 'fa fa-asterisk', 'fa fa-exclamation-circle', 'fa fa-gift', 'fa fa-leaf', 'fa fa-fire', 'fa fa-eye', 'fa fa-eye-slash', 'fa fa-exclamation-triangle', 'fa fa-plane', 'fa fa-calendar', 'fa fa-random', 'fa fa-comment', 'fa fa-magnet', 'fa fa-chevron-up', 'fa fa-chevron-down', 'fa fa-retweet', 'fa fa-shopping-cart', 'fa fa-folder', 'fa fa-folder-open', 'fa fa-arrows-v', 'fa fa-arrows-h', 'fa fa-bar-chart-o', 'fa fa-twitter-square', 'fa fa-facebook-square', 'fa fa-camera-retro', 'fa fa-key', 'fa fa-cogs', 'fa fa-comments', 'fa fa-thumbs-o-up', 'fa fa-thumbs-o-down', 'fa fa-star-half', 'fa fa-heart-o', 'fa fa-sign-out', 'fa fa-linkedin-square', 'fa fa-thumb-tack', 'fa fa-external-link', 'fa fa-sign-in', 'fa fa-trophy', 'fa fa-github-square', 'fa fa-upload', 'fa fa-lemon-o', 'fa fa-phone', 'fa fa-square-o', 'fa fa-bookmark-o', 'fa fa-phone-square', 'fa fa-twitter', 'fa fa-facebook', 'fa fa-github', 'fa fa-unlock', 'fa fa-credit-card', 'fa fa-rss', 'fa fa-hdd-o', 'fa fa-bullhorn', 'fa fa-bell', 'fa fa-certificate', 'fa fa-hand-o-right', 'fa fa-hand-o-left', 'fa fa-hand-o-up', 'fa fa-hand-o-down', 'fa fa-arrow-circle-left', 'fa fa-arrow-circle-right', 'fa fa-arrow-circle-up', 'fa fa-arrow-circle-down', 'fa fa-globe', 'fa fa-wrench', 'fa fa-tasks', 'fa fa-filter', 'fa fa-briefcase', 'fa fa-arrows-alt', 'fa fa-users', 'fa fa-link', 'fa fa-cloud', 'fa fa-flask', 'fa fa-scissors', 'fa fa-files-o', 'fa fa-paperclip', 'fa fa-floppy-o', 'fa fa-square', 'fa fa-bars', 'fa fa-list-ul', 'fa fa-list-ol', 'fa fa-strikethrough', 'fa fa-underline', 'fa fa-table', 'fa fa-magic', 'fa fa-truck', 'fa fa-pinterest', 'fa fa-pinterest-square', 'fa fa-google-plus-square', 'fa fa-google-plus', 'fa fa-money', 'fa fa-caret-down', 'fa fa-caret-up', 'fa fa-caret-left', 'fa fa-caret-right', 'fa fa-columns', 'fa fa-sort', 'fa fa-sort-asc', 'fa fa-sort-desc', 'fa fa-envelope', 'fa fa-linkedin', 'fa fa-undo', 'fa fa-gavel', 'fa fa-tachometer', 'fa fa-comment-o', 'fa fa-comments-o', 'fa fa-bolt', 'fa fa-sitemap', 'fa fa-umbrella', 'fa fa-clipboard', 'fa fa-lightbulb-o', 'fa fa-exchange', 'fa fa-cloud-download', 'fa fa-cloud-upload', 'fa fa-user-md', 'fa fa-stethoscope', 'fa fa-suitcase', 'fa fa-bell-o', 'fa fa-coffee', 'fa fa-cutlery', 'fa fa-file-text-o', 'fa fa-building-o', 'fa fa-hospital-o', 'fa fa-ambulance', 'fa fa-medkit', 'fa fa-fighter-jet', 'fa fa-beer', 'fa fa-h-square', 'fa fa-plus-square', 'fa fa-angle-double-left', 'fa fa-angle-double-right', 'fa fa-angle-double-up', 'fa fa-angle-double-down', 'fa fa-angle-left', 'fa fa-angle-right', 'fa fa-angle-up', 'fa fa-angle-down', 'fa fa-desktop', 'fa fa-laptop', 'fa fa-tablet', 'fa fa-mobile', 'fa fa-circle-o', 'fa fa-quote-left', 'fa fa-quote-right', 'fa fa-spinner', 'fa fa-circle', 'fa fa-reply', 'fa fa-github-alt', 'fa fa-folder-o', 'fa fa-folder-open-o', 'fa fa-smile-o', 'fa fa-frown-o', 'fa fa-meh-o', 'fa fa-gamepad', 'fa fa-keyboard-o', 'fa fa-flag-o', 'fa fa-flag-checkered', 'fa fa-terminal', 'fa fa-code', 'fa fa-reply-all', 'fa fa-mail-reply-all', 'fa fa-star-half-o', 'fa fa-location-arrow', 'fa fa-crop', 'fa fa-code-fork', 'fa fa-chain-broken', 'fa fa-question', 'fa fa-info', 'fa fa-exclamation', 'fa fa-superscript', 'fa fa-subscript', 'fa fa-eraser', 'fa fa-puzzle-piece', 'fa fa-microphone', 'fa fa-microphone-slash', 'fa fa-shield', 'fa fa-calendar-o', 'fa fa-fire-extinguisher', 'fa fa-rocket', 'fa fa-maxcdn', 'fa fa-chevron-circle-left', 'fa fa-chevron-circle-right', 'fa fa-chevron-circle-up', 'fa fa-chevron-circle-down', 'fa fa-html5', 'fa fa-css3', 'fa fa-anchor', 'fa fa-unlock-alt', 'fa fa-bullseye', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-rss-square', 'fa fa-play-circle', 'fa fa-ticket', 'fa fa-minus-square', 'fa fa-minus-square-o', 'fa fa-level-up', 'fa fa-level-down', 'fa fa-check-square', 'fa fa-pencil-square', 'fa fa-external-link-square', 'fa fa-share-square', 'fa fa-compass', 'fa fa-caret-square-o-down', 'fa fa-caret-square-o-up', 'fa fa-caret-square-o-right', 'fa fa-eur', 'fa fa-gbp', 'fa fa-usd', 'fa fa-inr', 'fa fa-jpy', 'fa fa-rub', 'fa fa-krw', 'fa fa-btc', 'fa fa-file', 'fa fa-file-text', 'fa fa-sort-alpha-asc', 'fa fa-sort-alpha-desc', 'fa fa-sort-amount-asc', 'fa fa-sort-amount-desc', 'fa fa-sort-numeric-asc', 'fa fa-sort-numeric-desc', 'fa fa-thumbs-up', 'fa fa-thumbs-down', 'fa fa-youtube-square', 'fa fa-youtube', 'fa fa-xing', 'fa fa-xing-square', 'fa fa-youtube-play', 'fa fa-dropbox', 'fa fa-stack-overflow', 'fa fa-instagram', 'fa fa-flickr', 'fa fa-adn', 'fa fa-bitbucket', 'fa fa-bitbucket-square', 'fa fa-tumblr', 'fa fa-tumblr-square', 'fa fa-long-arrow-down', 'fa fa-long-arrow-up', 'fa fa-long-arrow-left', 'fa fa-long-arrow-right', 'fa fa-apple', 'fa fa-windows', 'fa fa-android', 'fa fa-linux', 'fa fa-dribbble', 'fa fa-skype', 'fa fa-foursquare', 'fa fa-trello', 'fa fa-female', 'fa fa-male', 'fa fa-gittip', 'fa fa-sun-o', 'fa fa-moon-o', 'fa fa-archive', 'fa fa-bug', 'fa fa-vk', 'fa fa-weibo', 'fa fa-renren', 'fa fa-pagelines', 'fa fa-stack-exchange', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-o-left', 'fa fa-caret-square-o-left', 'fa fa-dot-circle-o', 'fa fa-wheelchair', 'fa fa-vimeo-square', 'fa fa-try', 'fa fa-plus-square-o' );
        }

        function get_easing_list() {
            return array( 'linear'=>'linear',
                'swing'=>'swing',
                'easeInBounce'=>'easeInBounce',
                'easeOutBounce'=>'easeOutBounce',
                'easeInOutBounce'=>'easeInOutBounce',
                'easeInQuad'=>'easeInQuad',
                'easeOutQuad'=>'easeOutQuad',
                'easeInOutQuad'=>'easeInOutQuad',
                'easeInCubic'=>'easeInCubic',
                'easeOutCubic'=>'easeOutCubic',
                'easeInOutCubic'=>'easeInOutCubic',
                'easeInQuart'=>'easeInQuart',
                'easeOutQuart'=>'easeOutQuart',
                'easeInOutQuart'=>'easeInOutQuart',
                'easeInQuint'=>'easeInQuint',
                'easeOutQuint'=>'easeOutQuint',
                'easeInOutQuint'=>'easeInOutQuint',
                'easeInSine'=>'easeInSine',
                'easeOutSine'=>'easeOutSine',
                'easeInOutSine'=>'easeInOutSine',
                'easeInExpo'=>'easeInExpo',
                'easeOutExpo'=>'easeOutExpo',
                'easeInOutExpo'=>'easeInOutExpo',
                'easeInCirc'=>'easeInCirc',
                'easeOutCirc'=>'easeOutCirc',
                'easeInOutCirc'=>'easeInOutCirc',
                'easeInElastic'=>'easeInElastic',
                'easeOutElastic'=>'easeOutElastic',
                'easeInOutElastic'=>'easeInOutElastic',
                'easeInBack'=>'easeInBack',
                'easeOutBack'=>'easeOutBack',
                'easeInOutBack'=>'easeInOutBack',
            );
        }

        function get_image_sizes() {
            $cache_name = 'tn_image_sizes_list';
            $list = wp_cache_get( $cache_name, 'addway' );

            if ( !is_array( $list ) ) {
                global $_wp_additional_image_sizes;
                $sizes = array();
                foreach ( get_intermediate_image_sizes() as $s ) {
                    $sizes[ $s ] = array( 0, 0 );
                    if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
                        $sizes[ $s ][0] = get_option( $s . '_size_w' );
                        $sizes[ $s ][1] = get_option( $s . '_size_h' );
                    }else {
                        if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
                            $sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
                    }
                }

                foreach ( $sizes as $size => $atts ) {
                    $list[$size] = $size . ' ' . implode( 'x', $atts ) . "\n";
                }
                wp_cache_add( $cache_name, $list, 'addway' );
            }

            return $list;
        }

        function get_term_list( $taxonomy ) {
            $cache_name = 'tn_'. $taxonomy. '_list';
            $list = wp_cache_get( $cache_name, 'addway' );
            if ( !is_array( $list ) ) {
                $list = array();
                $terms = get_terms( $taxonomy, 'hide_empty=0' );
                foreach ( $terms as $term ) {
                    $list[$term->term_id] = $term->name;
                }
                wp_cache_add( $cache_name, $list, 'addway' );
            }

            return $list;
        }

        function get_post_types() {
            $excludes = array( 'mediapage', 'revision', 'nav_menu_item' );

            $cache_name = 'tn_ptypes_list';
            $ptypes_list = wp_cache_get( $cache_name, 'addway' );
            if ( !is_array( $ptypes_list ) ) {
                $ptypes_list = array();
                $post_types = get_post_types( null, 'objects' );
                foreach ( $post_types as $post_type ) {
                    if ( in_array( $post_type->name, $excludes ) )
                        continue;
                    $ptypes_list[$post_type->name] = $post_type->labels->singular_name;
                }
                wp_cache_add( $cache_name, $ptypes_list, 'addway' );
            }

            return $ptypes_list;
        }

        function get_post_list( $post_type = 'post' ) {
            $cache_name = 'tn_'. $post_type. '_list';
            $post_list = wp_cache_get( $cache_name, 'addway' );
            if ( !is_array( $post_list ) ) {
                $post_list = array();
                $posts = get_posts( 'numberposts=100&post_type='. $post_type );
                foreach ( $posts as $post ) {
                    $post_list[$post->ID] = $post->post_title;
                }
                wp_cache_add( $cache_name, $post_list, 'addway' );
            }

            return $post_list;
        }

        function get_page() {
            return $this->get_post_list( 'page' );
        }

        function get_category() {
            $cache = wp_cache_get( 'tn_category_list', 'addway' );
            if ( !is_array( $cache ) ) {
                $cache = array();
                $categories = get_categories( 'hide_empty=0' );
                foreach ( $categories as $category ) {
                    $cache[$category->slug] = $category->name;
                }
                wp_cache_add( 'tn_category_list', $cache, 'addway' );
            }

            return $cache;
        }

        function get_link_category() {
            $cache = wp_cache_get( 'tn_link_categories', 'addway' );
            if ( !is_array( $cache ) ) {
                $cache = array();
                $categories = get_categories( 'type=link' );
                foreach ( $categories as $category ) {
                    $cache[$category->term_id] = $category->name;
                }
                wp_cache_add( 'tn_link_categories', $cache, 'addway' );
            }

            return $cache;
        }

        function get_tag() {
            $cache = wp_cache_get( 'tn_tag_list', 'addway' );
            if ( !is_array( $cache ) ) {
                $cache = array();
                $tags = get_tags( 'hide_empty=0' );
                foreach ( $tags as $tag ) {
                    $cache[$tag->term_id] = $tag->name;
                }
                wp_cache_add( 'tn_tag_list', $cache, 'addway' );
            }

            return $cache;
        }

        function get_sticky_posts() {
            $cache = wp_cache_get( 'tn_sticky_posts', 'addway' );
            if ( !is_array( $cache ) ) {
                $cache = array();
                $tags = get_tags( 'hide_empty=0' );
                foreach ( $tags as $tag ) {
                    $cache[$tag->term_id] = $tag->name;
                }
                wp_cache_add( 'tn_sticky_posts', $cache, 'addway' );
            }

            return $cache;
        }

        function get_widget_area() {
            $cache = wp_cache_get( 'tn_widget_areas', 'addway' );
            if ( !is_array( $cache ) ) {
                $options = tn_get_options();
                // The reason why not do an is_array verification here is if it is not array, then it must be a bug,
                // we can get an error message from here thus know something is wrong
                $cache = empty( $options['widget_areas'] ) ? array() : $options['widget_areas'];
                wp_cache_add( 'tn_widget_areas', $cache, 'addway' );
            }

            return $cache;
        }
    }
}
?>
