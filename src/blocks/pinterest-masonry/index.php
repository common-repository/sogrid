<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pinterest Masonry Block
 */
class Sogrid_Pinterest_Masonry extends Sogrid_Block{

    /**
     * Constructor
     */
    function __construct(){

        $this->name = 'pinterest-masonry';
        $this->slug = 'sogrid/pinterest-masonry';

        $this->attributes = array(
            'uid' => array(
                'type' => 'string',
                'default' => ''
            ),
            'isAuthorVisible' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'isDateVisible' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'areCategoriesVisible' => array(
                'type' => 'boolean',
                'default' => true
            ),
            'categoriesSelected' => array(
                'type' => 'array',
                'default' => array(),
                'items'   => [
                    'type' => 'object',
                ]
            ),
            'postsNumber' => array(
                'type' => 'number',
                'default' => 6
            ),
            'columns' => array(
                'type' => 'number',
                'default' => 3
            ),
            'tabletColumns' => array(
                'type' => 'number',
                'default' => 2
            ),
            'mobileColumns' => array(
                'type' => 'number',
                'default' => 1
            ),
            'fontSize' => array(
                'type' => 'number',
                'default' => 16
            ),
            'tabletFontSize' => array(
                'type' => 'number',
                'default' => 16
            ),
            'mobileFontSize' => array(
                'type' => 'number',
                'default' => 16
            ),
            'titleColor' => array(
                'type' => 'string',
                'default' => '#191919'
            ),
            'categoryColor' => array(
                'type' => 'string',
                'default' => '#494949'
            ),
            'excerptColor' => array(
                'type' => 'string',
                'default' => '#555555'
            ),
            'authorColor' => array(
                'type' => 'string',
                'default' => '#777777'
            ),
            'dateColor' => array(
                'type' => 'string',
                'default' => '#777777'
            ),
            'borderColor' => array(
                'type' => 'string',
                'default' => '#f1f1f1'
            ),
            'marginTop' => array(
                'type' => 'number',
                'default' => 0
            ),
            'marginBottom' => array(
                'type' => 'number',
                'default' => 20
            ),
            'paddingTop' => array(
                'type' => 'number',
                'default' => 20
            ),
            'paddingBottom' => array(
                'type' => 'number',
                'default' => 20
            ),
            'paddingLeft' => array(
                'type' => 'number',
                'default' => 0
            ),
            'paddingRight' => array(
                'type' => 'number',
                'default' => 0
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => 'rgba(0,0,0,0)'
            ),
            'isExcerptVisible' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'isPaginationEnabled' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'paginationMaxPages' => array(
                'type' => 'number',
                'default' => 3,
            ),
            'paginationPos' => array(
                'type' => 'string',
                'default' => 'top',
            ),
            'paginationColor' => array(
                'type' => 'string',
                'default' => '#333333',
            ),
            'paginationBgColor' => array(
                'type' => 'string',
                'default' => '#e9e9e9',
            ),
            'paginationActiveColor' => array(
                'type' => 'string',
                'default' => '#ffffff',
            ),
            'paginationActiveBgColor' => array(
                'type' => 'string',
                'default' => '#d32f2f',
            ),
            'isReadMoreVisible' => array(
                'type' => 'boolean',
                'default' => false
            ),
            'readMoreText' => array(
                'type' => 'string',
                'default' => __('Read More', 'sogrid')
            ),
            'readmoreColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'readmoreBGColor' => array(
                'type' => 'string',
                'default' => '#d32f2f'
            ),
            'postsOrder' => array(
                'type' => 'string',
                'default' => 'date'
            ),
        );

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_ajax_sogrid_pinterest_masonry', array( $this, 'ajaxRender' ));
        add_action( 'wp_ajax_nopriv_sogrid_pinterest_masonry', array( $this, 'ajaxRender' ));
    }

    /**
     * Render the posts
     *
     * @param array $recent_posts
     * @param array $attributes
     * @return string
     */
    function renderItems( $recent_posts, $attributes ){

        $output = '';

        foreach( $recent_posts as $post ){

            $thumbnail = '';
            $has_thumbnail = '';

            if( has_post_thumbnail( $post ) ){
                $thumbnail = Sogrid_Helpers::renderPostThumbnail( $post->ID, $attributes );
                $has_thumbnail = ' sogrid__entry--has-thumbnail';
            }

            $output .= '
                      
            <article class="sogrid__entry'.$has_thumbnail.'">
            
                '.$thumbnail.'

                <div class="sogrid__entry__content">

                    '.Sogrid_Helpers::renderPostCategories( get_the_category($post->ID), $attributes ).'

                    <h3 class="sogrid__entry__title">
                        <a href="'.esc_url( get_the_permalink($post->ID) ).'">'.esc_html( get_the_title($post->ID) ).'</a>
                    </h3>

                    '.Sogrid_Helpers::renderPostMeta( $post, $attributes ).'
                    
                    '.Sogrid_Helpers::renderPostExcerpt( $post, $attributes ).'

                    '.Sogrid_Helpers::renderPostReadMore( $post, $attributes ).'

                </div>

            </article>';
    
        }

        return $output;
    }
    
    /**
     * Render Style
     */
    protected function renderStyle( $attributes ){

        extract( $attributes );
    
        return "
            #{$uid}.sogrid--pinterest-masonry{
                font-size: {$mobileFontSize}px;
                padding: {$paddingTop}px {$paddingRight}px {$paddingBottom}px {$paddingLeft}px;
                margin-top: {$marginTop}px;
                margin-bottom: {$marginBottom}px;
                background-color: {$backgroundColor};
            }

            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__content{
                border-color: {$borderColor};
            }

            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__title a,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__title a:hover,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__title a:focus,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__title a:visited{
                color: {$titleColor};
            }
    
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__meta{
                color: {$dateColor};
            }
    
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__meta .sogrid__entry__author,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__meta .sogrid__entry__author:hover,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__meta .sogrid__entry__author:focus,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__meta .sogrid__entry__author:visited{
                color: {$authorColor};
            }

            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__categories a,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__categories a:hover,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__categories a:focus,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__categories a:visited{
                color: {$categoryColor};
            }
    
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__excerpt{
                color: {$excerptColor};
            } 

            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__readmore,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__readmore:hover,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__readmore:focus,
            #{$uid}.sogrid--pinterest-masonry .sogrid__entry__readmore:visited{
                color: {$readmoreColor};
                background-color: {$readmoreBGColor};
            }

            #{$uid}.sogrid--pinterest-masonry .sogrid__pagination span{
                color: {$paginationColor};
                background-color: {$paginationBgColor};
            }
    
            #{$uid}.sogrid--pinterest-masonry .sogrid__pagination span.__active{
                color: {$paginationActiveColor};
                background-color: {$paginationActiveBgColor};
            }

            @media all and (min-width: 768px) {
                #{$uid}.sogrid--pinterest-masonry{
                    font-size: {$tabletFontSize}px;
                }
            }
    
            @media all and (min-width: 992px) {
                #{$uid}.sogrid--pinterest-masonry{
                    font-size: {$fontSize}px;
                }
            }

        ";
    
    }

}

new Sogrid_Pinterest_Masonry;
