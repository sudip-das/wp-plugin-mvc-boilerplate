<?php
namespace pluginname\component;

class Util_Wpquery {


    static function get_query( $atts ){

        $default_atts = array(
            //'container' => 'ul',

            'id' => '',
            'classes' => '',


            'posts_per_page' => '6',
            'paging' => 'true',
            //'template' => 'default',
            'post_type' => 'post',
            'post_format' => '',
            'category__in' => '',
            'category__not_in' => '',
            'tag__in' => '',
            'tag__not_in' => '',
            'taxonomy' => '',
            'taxonomy_terms' => '',
            'taxonomy_operator' => 'IN',
            'meta_key' => '',
            'meta_value' => '',
            'meta_compare' => 'IN',
            'year' => '',
            'month' => '',
            'day' => '',
            'author' => '',
            'search' => '',
            'post__in' => '',
            'post__not_in' => '',
            'post_status' => 'publish',
            'order' => 'DESC',
            'orderby' => 'date',
            'offset' => '0',
        );


//        $a = shortcode_atts( $default_atts , $atts);

        $a = array_merge($default_atts, $atts );

        // Containers Options
//        $container = $a['container'];
        $classes = $a['classes'];

        $posts_per_page = $a['posts_per_page'];
        $paging = $a['paging'];

        // Repeater
        $template = $a['template'];
        //$template_type = preg_split('/(?=\d)/', $template, 2); // split $template value at number to determine type
        //$template_type = $template_type[0]; // default | template_

        // Post type & Format
        $post_type = explode(",", $a['post_type']);
        $post_format = $a['post_format'];

        // Cat & Tag
        $category__in = trim($a['category__in']);
        $category__not_in = trim($a['category__not_in']);
        $tag__in = $a['tag__in'];
        $tag__not_in = $a['tag__not_in'];

        // Taxonomy
        $taxonomy = $a['taxonomy'];
        $taxonomy_terms = $a['taxonomy_terms'];
        $taxonomy_operator = $a['taxonomy_operator'];

        // Custom Fields
        $meta_key = $a['meta_key'];
        $meta_value = $a['meta_value'];
        $meta_compare = $a['meta_compare'];

        // Search
        $s = $a['search'];

        // Date
        $year = $a['year'];
        $month = $a['month'];
        $day = $a['day'];

        // Author ID
        $author_id = $a['author'];

        // Ordering
        $order = $a['order'];
        $orderby = $a['orderby'];

        // Exclude, Offset, Status
        $post__in = $a['post__in'];
        $post__not_in = $a['post__not_in'];
        $offset = $a['offset'];
        $post_status = $a['post_status'];

        // Lang Support
//        $lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : ''; // WPML - http://wpml.org
//        if (function_exists('pll_current_language')) // Polylang - https://wordpress.org/plugins/polylang/
//            $lang = pll_current_language();
//        if (function_exists('qtrans_getLanguage')) // qTranslate - https://wordpress.org/plugins/qtranslate/
//            $lang = qtrans_getLanguage();


        // $args
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type'             => $post_type,
            'posts_per_page'        => $posts_per_page,
            'offset'                => $offset,
            'order'                 => $order,
            'orderby'               => $orderby,
            'post_status'           => $post_status,
            'ignore_sticky_posts'   => false,
            'paged'                 => $paged,
        );

        // Post Format & taxonomy
        if(!empty($post_format) || !empty($taxonomy)){
            $args['tax_query'] = array(
                'relation' => 'AND',
                self::get_tax_query($post_format, $taxonomy, $taxonomy_terms, $taxonomy_operator)
            );
        }

        // Category
        if(!empty($category__in)){
            $include_cats = explode(",",$category__in);
            $args['category__in'] = $include_cats;
        }

        // Category Not In
        if(!empty($category__not_in)){
            $exclude_cats = explode(",",$category__not_in);
            $args['category__not_in'] = $exclude_cats;
        }

        // Tag
        if(!empty($tag__in)){
            $include_tags = explode(",",$tag__in);
            $args['tag__in'] = $include_tags;
        }

        // Tag Not In
        if(!empty($tag__not_in)){
            $exclude_tags = explode(",",$tag__not_in);
            $args['tag__not_in'] = $exclude_tags;
        }

        // Date (not using date_query as there was issue with year/month archives)
        if(!empty($year)){
            $args['year'] = $year;
        }
        if(!empty($month)){
            $args['monthnum'] = $month;
        }
        if(!empty($day)){
            $args['day'] = $day;
        }

        // Meta Query
        if(!empty($meta_key) && !empty($meta_value)){
            $args['meta_query'] = array(
                self::get_meta_query($meta_key, $meta_value, $meta_compare)
            );
        }

        // Author
        if(!empty($author_id)){
            $args['author'] = $author_id;
        }

        // Search Term
        if(!empty($s)){
            $args['s'] = $s;
        }

        // Meta_key, used for ordering by meta value
        if(!empty($meta_key)){
            $args['meta_key'] = $meta_key;
        }

        // include posts
        if(!empty($post__in)){
            $post__in = explode(",",$post__in);
            $args['post__in'] = $post__in;
        }

        // Exclude posts
        if(!empty($post__not_in)){
            $post__not_in = explode(",",$post__not_in);
            $args['post__not_in'] = $post__not_in;
        }

        // Language
        if(!empty($lang)){
            $args['lang'] = $lang;
        }


        // WP_Query
        $eq_query = new \WP_Query( $args );
        $eq_total_posts = $eq_query->found_posts - $offset;


        return array(
            'query' => $eq_query,
            'total' => $eq_total_posts,
            'atts' => $a

        );
    }

    static function get_tax_query($post_format, $taxonomy, $taxonomy_terms, $taxonomy_operator){

        // Taxonomy [ONLY]
        if(!empty($taxonomy) && !empty($taxonomy_terms) && !empty($taxonomy_operator) && empty($post_format)){
            $the_terms = explode(",", $taxonomy_terms);
            $args = array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $the_terms,
                'operator' => $taxonomy_operator,
            );
            return $args;
        }

        // Post Format [ONLY]
        if(!empty($post_format) && empty($taxonomy)){
            $format = "post-format-$post_format";
            //If query is for standard then we need to filter by NOT IN
            if($format == 'post-format-standard'){
                if (($post_formats = get_theme_support('post-formats')) && is_array($post_formats[0]) && count($post_formats[0])) {
                    $terms = array();
                    foreach ($post_formats[0] as $format) {
                        $terms[] = 'post-format-'.$format;
                    }
                }
                $args = array(
                    'taxonomy' => 'post_format',
                    'terms' => $terms,
                    'field' => 'slug',
                    'operator' => 'NOT IN',
                );
            }else{
                $args = array(
                    'taxonomy' => 'post_format',
                    'field' => 'slug',
                    'terms' => array($format),
                );
            }
            return $args;
        }

        // Taxonomy && Post Format [COMBINED]
        if(!empty($post_format) && !empty($taxonomy) && !empty($taxonomy_terms) && !empty($taxonomy_operator)){
            $the_terms = explode(",", $taxonomy_terms);
            $args = array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $the_terms,
                'operator' => $taxonomy_operator,
            );
            $format = "post-format-$post_format";
            //If query is for standard then we need to filter by NOT IN
            if($format == 'post-format-standard'){
                if (($post_formats = get_theme_support('post-formats')) && is_array($post_formats[0]) && count($post_formats[0])) {
                    $terms = array();
                    foreach ($post_formats[0] as $format) {
                        $terms[] = 'post-format-'.$format;
                    }
                }
                $format_args = array(
                    'taxonomy' => 'post_format',
                    'terms' => $terms,
                    'field' => 'slug',
                    'operator' => 'NOT IN',
                );
            }else{
                $format_args = array(
                    'taxonomy' => 'post_format',
                    'field' => 'slug',
                    'terms' => array($format),
                );
            }
            $args[] = $format_args; // Combined format and tax $args
            return $args;
        }
    }

    static function get_meta_query($meta_key, $meta_value, $meta_compare){

        if(!empty($meta_key) && !empty($meta_value)){
            // See the docs (http://codex.wordpress.org/Class_Reference/WP_Meta_Query)
            if($meta_compare === 'IN' || $meta_compare === 'NOT IN' || $meta_compare === 'BETWEEN' || $meta_compare === 'NOT BETWEEN'){
                // Remove all whitespace for meta_value because it needs to be an exact match
                $mv_trimmed = preg_replace('/\s+/', ' ', $meta_value); // Trim whitespace
                $meta_values = str_replace(', ', ',', $mv_trimmed); // Replace [term, term] with [term,term]
                $meta_values = explode(",", $meta_values);
            }else{
                $meta_values = $meta_value;
            }
            $args = array(
                'key' => $meta_key,
                'value' => $meta_values,
                'compare' => $meta_compare,
            );
            return $args;
        }
    }

}