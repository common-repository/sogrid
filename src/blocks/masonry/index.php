<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Masonry Block
 */
class Sogrid_Masonry extends Sogrid_Block{

    /**
     * Constructor
     */
    function __construct(){

        $this->name = 'masonry';
        $this->slug = 'sogrid/masonry';

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
                'default' => '#5e35b1'
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
     * Render Style
     */
    protected function renderStyle( $attributes ){

        extract( $attributes );
    
        return "
            #{$uid}.sogrid--masonry{
                font-size: {$mobileFontSize}px;
                padding: {$paddingTop}px {$paddingRight}px {$paddingBottom}px {$paddingLeft}px;
                margin-top: {$marginTop}px;
                margin-bottom: {$marginBottom}px;
                background-color: {$backgroundColor};
            }

            #{$uid}.sogrid--masonry .sogrid__entry__title a,
            #{$uid}.sogrid--masonry .sogrid__entry__title a:focus,
            #{$uid}.sogrid--masonry .sogrid__entry__title a:hover,
            #{$uid}.sogrid--masonry .sogrid__entry__title a:visited{
                color: {$titleColor};
            }
    
            #{$uid}.sogrid--masonry .sogrid__entry__meta{
                color: {$dateColor};
            }
    
            #{$uid}.sogrid--masonry .sogrid__entry__meta .sogrid__entry__author,
            #{$uid}.sogrid--masonry .sogrid__entry__meta .sogrid__entry__author:hover,
            #{$uid}.sogrid--masonry .sogrid__entry__meta .sogrid__entry__author:focus,
            #{$uid}.sogrid--masonry .sogrid__entry__meta .sogrid__entry__author:visited{
                color: {$authorColor};
            }

            #{$uid}.sogrid--masonry .sogrid__entry__categories a,
            #{$uid}.sogrid--masonry .sogrid__entry__categories a:hover,
            #{$uid}.sogrid--masonry .sogrid__entry__categories a:focus,
            #{$uid}.sogrid--masonry .sogrid__entry__categories a:visited{
                color: {$categoryColor};
            }
    
            #{$uid}.sogrid--masonry .sogrid__entry__excerpt{
                color: {$excerptColor};
            }
    
            #{$uid}.sogrid--masonry .sogrid__entry__readmore,
            #{$uid}.sogrid--masonry .sogrid__entry__readmore:focus,
            #{$uid}.sogrid--masonry .sogrid__entry__readmore:hover,
            #{$uid}.sogrid--masonry .sogrid__entry__readmore:visited{
                color: {$readmoreColor};
                background-color: {$readmoreBGColor};
            }   

            #{$uid}.sogrid--masonry .sogrid__pagination span{
                color: {$paginationColor};
                background-color: {$paginationBgColor};
            }
    
            #{$uid}.sogrid--masonry .sogrid__pagination span.__active{
                color: {$paginationActiveColor};
                background-color: {$paginationActiveBgColor};
            }

            @media all and (min-width: 768px) {
                #{$uid}.sogrid--masonry{
                    font-size: {$tabletFontSize}px;
                }
            }
    
            @media all and (min-width: 992px) {
                #{$uid}.sogrid--masonry{
                    font-size: {$fontSize}px;
                }
            }

        ";
    
    }

}

new Sogrid_Masonry;
