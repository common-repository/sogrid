<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Overlay Grid
 */
class Sogrid_Overlay extends Sogrid_Block{

    /**
     * Constructor
     */
    function __construct(){

        $this->name = 'overlay';
        $this->slug = 'sogrid/overlay';

        $this->attributes = array(
            'uid' => array(
                'type' => 'string',
                'default' => ''
            ),
            'isDateVisible' => array(
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
                'default' => '#ffffff'
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
                'default' => '#eeeeee'
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
            'postsOrder' => array(
                'type' => 'string',
                'default' => 'date'
            ),
        );

        add_action( 'init', array( $this, 'init' ) );
        add_action( 'wp_ajax_sogrid_' . $this->name , array( $this, 'ajaxRender' ));
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
    
        foreach( $recent_posts as $post ){

            $thumbnail = '';
            $has_thumbnail = '';

            if( has_post_thumbnail( $post ) ){
                $thumbnail = $this->renderPostThumbnail( $post->ID, array() );
                $has_thumbnail = ' sogrid__entry--has-thumbnail';
            }
            else{
                continue;
            }

            $output .= '
                      
            <article class="sogrid__entry'.$has_thumbnail.'">
            
                '.$thumbnail.'

                <div class="sogrid__entry__overlay">

                    <h3 class="sogrid__entry__title">
                        <a href="'.esc_url( get_the_permalink($post->ID) ).'">'.esc_html( get_the_title($post->ID) ).'</a>
                    </h3>
                    
                    '.$this->renderPostMeta( $post, $attributes ).'
                </div>

                <a href="'.esc_url( get_the_permalink($post->ID) ).'" class="sogrid__entry__overlay__link"></a>

            </article>';
    
        }

        return $output;
    }

    /**
     * Renders Post Meta ( author, date ... )
     *
     * @param array $attributes
     * @return string
     */
    private function renderPostMeta( $post, $attributes ){

        $meta = '';

        if( $attributes['isDateVisible'] ){
            $meta = '<div class="sogrid__entry__meta">';
            $meta .= '<span class="sogrid__entry__date">'.esc_html( get_the_date('', $post->ID) ).'</span>';
            $meta .= '</div>';
        }

        return $meta;
    }

    /**
     * Renders Post Thumbnail
     *
     * @param array $attributes
     * @return string
     */
    private function renderPostThumbnail( $post_id ){

        $thumbnail = '<a class="sogrid__entry__thumbnail" href="'.esc_url( get_the_permalink($post_id) ).'">';
        $thumbnail .= get_the_post_thumbnail( $post_id );
        $thumbnail .= '</a>';
        
        return $thumbnail;
    }

    /**
     * Render Style
     */
    protected function renderStyle( $attributes ){

        extract( $attributes );
    
        return "
            #{$uid}.sogrid--overlay{
                font-size: {$mobileFontSize}px;
                padding: {$paddingTop}px {$paddingRight}px {$paddingBottom}px {$paddingLeft}px;
                margin-top: {$marginTop}px;
                margin-bottom: {$marginBottom}px;
            }

            #{$uid}.sogrid--overlay .sogrid__entry__title a,
            #{$uid}.sogrid--overlay .sogrid__entry__title a:hover,
            #{$uid}.sogrid--overlay .sogrid__entry__title a:focus,
            #{$uid}.sogrid--overlay .sogrid__entry__title a:visited{
                color: {$titleColor};
            }
    
            #{$uid}.sogrid--overlay .sogrid__entry__meta{
                color: {$dateColor};
            }
    
            #{$uid}.sogrid--overlay .sogrid__entry__meta .sogrid__entry__author,
            #{$uid}.sogrid--overlay .sogrid__entry__meta .sogrid__entry__author:hover,
            #{$uid}.sogrid--overlay .sogrid__entry__meta .sogrid__entry__author:focus,
            #{$uid}.sogrid--overlay .sogrid__entry__meta .sogrid__entry__author:visited{
                color: {$authorColor};
            }

            #{$uid}.sogrid--overlay .sogrid__entry__categories a,
            #{$uid}.sogrid--overlay .sogrid__entry__categories a:hover,
            #{$uid}.sogrid--overlay .sogrid__entry__categories a:focus,
            #{$uid}.sogrid--overlay .sogrid__entry__categories a:visited{
                color: {$categoryColor};
            }
    
            #{$uid}.sogrid--overlay .sogrid__entry__excerpt{
                color: {$excerptColor};
            }
    
            #{$uid}.sogrid--overlay .sogrid__entry__readmore,
            #{$uid}.sogrid--overlay .sogrid__entry__readmore:hover,
            #{$uid}.sogrid--overlay .sogrid__entry__readmore:focus,
            #{$uid}.sogrid--overlay .sogrid__entry__readmore:visited{
                color: {$readmoreColor};
                background-color: {$readmoreBGColor};
            }   

            #{$uid}.sogrid--overlay .sogrid__pagination span{
                color: {$paginationColor};
                background-color: {$paginationBgColor};
            }
    
            #{$uid}.sogrid--overlay .sogrid__pagination span.__active{
                color: {$paginationActiveColor};
                background-color: {$paginationActiveBgColor};
            }

            @media all and (min-width: 768px) {
                #{$uid}.sogrid--overlay{
                    font-size: {$tabletFontSize}px;
                }
            }
    
            @media all and (min-width: 992px) {
                #{$uid}.sogrid--overlay{
                    font-size: {$fontSize}px;
                }
            }

        ";
    
    }

}

new Sogrid_Overlay;
