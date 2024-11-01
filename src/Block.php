<?php

/**
 * Block abstract class
 */
abstract class Sogrid_Block{

    /** @var array */
    public $attributes;

    /** @var string ex sogrid/bordered */
    public $slug = '';

    /** @var string ex bordered */
    public $name = '';

    /**
     * Render Style
     *
     * @param array $attributes
     */
    abstract protected function renderStyle( $attributes );

    /**
     * Register the block
     */
    function init(){

        // Check if the register function exists
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        register_block_type( $this->slug, array(
            'attributes' => $this->attributes,
            'render_callback' => array( $this, 'render' ),
        ));
    }

    /**
     * Server-side Rendering
     */
    function render( $attributes ) {

        $query_data = array(
            'paged' => 1,
            'posts_per_page' => (int)$attributes['postsNumber'],
            'cat' => Sogrid_Helpers::prepareQueryCategories( $attributes['categoriesSelected'] ),
        );

        $query_data = Sogrid_Helpers::prepareRandomQuery( $attributes, $query_data );
        $query = Sogrid_Helpers::query($query_data);

        $recent_posts = $query['posts'];

        if ( count( $recent_posts ) === 0 ) {
            return 'No posts';
        }

        $itemsOutput = $this->renderItems( $recent_posts, $attributes );
        $paginationOutput = Sogrid_Helpers::renderPagination( $this->slug, $attributes, $query );
        $desktop_cols = isset( $attributes['columns'] ) ? esc_attr( $attributes['columns'] ) : '';
        $tablet_cols = isset( $attributes['tabletColumns'] ) ? esc_attr( $attributes['tabletColumns'] ) : '';
        $mobile_cols = isset( $attributes['mobileColumns'] ) ? esc_attr( $attributes['mobileColumns'] ) :  '';

        return '
                <style>'.$this->renderStyle($attributes).'</style>

                <script>var '. str_replace('-', '_', $attributes['uid'] ) .' = ' . wp_json_encode($attributes) . ';</script>

                <div
                    id='.esc_attr( $attributes['uid'] ).'
                    class="sogrid sogrid--'.$this->name.'"
                    data-desktop="'.$desktop_cols.'"
                    data-tablet="'.$tablet_cols.'"
                    data-mobile="'.$mobile_cols.'"
                >

                    '.( $attributes['paginationPos'] === 'top' ? $paginationOutput : '' ).'

                    <div class="sogrid__posts" data-page="1">' . $itemsOutput . '</div>

                    '.( $attributes['paginationPos'] === 'bottom' ? $paginationOutput : '' ).'

                </div>';
    }

    /**
     * Ajax render ( pagination )
     */
    function ajaxRender(){
        Sogrid_Helpers::ajax_render( $this );
        die;
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
}
