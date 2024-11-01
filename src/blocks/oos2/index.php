<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * One Others Style 2 Block
 */
class Sogrid_OOS2 extends Sogrid_Block{

    /**
     * Constructor
     */
    function __construct(){

        $this->name = 'oos2';
        $this->slug = 'sogrid/oos2';

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
                'default' => 5
            ),
            'maxColumns' => array(
                'type' => 'number',
                'default' => 2
            ),
            'columns' => array(
                'type' => 'number',
                'default' => 2
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
            'backgroundColor' => array(
                'type' => 'string',
                'default' => 'rgba(0,0,0,0)'
            ),
            'titleColor' => array(
                'type' => 'string',
                'default' => '#96588a'
            ),
            'categoryColor' => array(
                'type' => 'string',
                'default' => '#e53935'
            ),
            'excerptColor' => array(
                'type' => 'string',
                'default' => '#555555'
            ),
            'readmoreColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'readmoreBGColor' => array(
                'type' => 'string',
                'default' => '#d32f2f'
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
                'default' => '#cccccc'
            ),
            'hasBorderedImage' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'marginTop' => array(
                'type' => 'number',
                'default' => 0
            ),
            'marginBottom' => array(
                'type' => 'number',
                'default' => 20
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
                'default' => true
            ),
            'readMoreText' => array(
                'type' => 'string',
                'default' => __('Read More', 'sogrid')
            ),
            'postsOrder' => array(
                'type' => 'string',
                'default' => 'date'
            ),
        );

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_ajax_sogrid_' . $this->name, array( $this, 'ajaxRender' ));
        add_action( 'wp_ajax_nopriv_sogrid_' . $this->name, array( $this, 'ajaxRender' ));
    }

    /**
     * Overriden
     * Render the posts
     *
     * @param array $recent_posts
     * @param array $attributes
     * @return string
     */
    function renderItems($recent_posts, $attributes){

        $output = '';
        $count = 0;
    
        foreach( $recent_posts as $post ){

            $thumbnail = '';
            $has_thumbnail = '';

            if( has_post_thumbnail( $post ) ){
                $thumbnail = Sogrid_Helpers::renderPostThumbnail( $post->ID, $attributes );
                $has_thumbnail = ' sogrid__entry--has-thumbnail';
            }

            if( $count === 0 ){
                $output .= '<div class="sogrid__one">';
            }
            elseif( $count === 1 ){
                $output .= '</div><div class="sogrid__others">';
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
    
            if( $count === count( $recent_posts ) - 1 ){
                $output .= '</div>';
            }

            $count += 1;
        }

        return $output;
    }

    /**
     * Render Style
     */
    protected function renderStyle( $attributes ){

        extract( $attributes );
    
        return "
            #{$uid}.sogrid--oos2{
                font-size: {$mobileFontSize}px;
                background-color: {$backgroundColor};
                margin-top: {$marginTop}px;
                margin-bottom: {$marginBottom}px;
            }

            #{$uid}.sogrid--oos2 .sogrid__others,
            #{$uid}.sogrid--oos2 .sogrid__others .sogrid__entry,        
            #{$uid}.sogrid--oos2 .sogrid__one{
                border-color: {$borderColor};
            }

            #{$uid}.sogrid--oos2 .sogrid__entry__title a,
            #{$uid}.sogrid--oos2 .sogrid__entry__title a:hover,
            #{$uid}.sogrid--oos2 .sogrid__entry__title a:focus,
            #{$uid}.sogrid--oos2 .sogrid__entry__title a:visited{
                color: {$titleColor};
            }
    
            #{$uid}.sogrid--oos2 .sogrid__entry__meta{
                color: {$dateColor};
            }
    
            #{$uid}.sogrid--oos2 .sogrid__entry__meta .sogrid__entry__author{
                color: {$authorColor};
            }

            #{$uid}.sogrid--oos2 .sogrid__entry__categories a,
            #{$uid}.sogrid--oos2 .sogrid__entry__categories a:hover,
            #{$uid}.sogrid--oos2 .sogrid__entry__categories a:focus,
            #{$uid}.sogrid--oos2 .sogrid__entry__categories a:visited{
                color: {$categoryColor};
            }
    
            #{$uid}.sogrid--oos2 .sogrid__entry__excerpt{
                color: {$excerptColor};
            }
    
            #{$uid}.sogrid--oos2 .sogrid__entry__readmore,
            #{$uid}.sogrid--oos2 .sogrid__entry__readmore:hover,
            #{$uid}.sogrid--oos2 .sogrid__entry__readmore:focus,
            #{$uid}.sogrid--oos2 .sogrid__entry__readmore:visited{
                color: {$readmoreColor};
                background-color: {$readmoreBGColor};
            }

            #{$uid}.sogrid--oos2 .sogrid__pagination{
                border-color: {$borderColor};
            }

            #{$uid}.sogrid--oos2 .sogrid__pagination span{
                color: {$paginationColor};
                background-color: {$paginationBgColor};
            }
    
            #{$uid}.sogrid--oos2 .sogrid__pagination span.__active{
                color: {$paginationActiveColor};
                background-color: {$paginationActiveBgColor};
            }

            @media all and (min-width: 768px) {
                #{$uid}.sogrid--oos2{
                    font-size: {$tabletFontSize}px;
                }
            }
    
            @media all and (min-width: 992px) {
                #{$uid}.sogrid--oos2{
                    font-size: {$fontSize}px;
                }
            }

        ";
    
    }

}

new Sogrid_OOS2;
