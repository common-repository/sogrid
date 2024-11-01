<?php
/**
 * Helpers
 */

class Sogrid_Helpers{

    /**
	 * Dumper ( debuging )
	 *
	 * @param any $data
	 * @param boolean $die
	 * @return void
	 */
	static function dump( $data, $die = true ){

		echo '<pre>';
		var_dump( $data );
		echo '</pre>';

		if( $die ) die;

    }

    /**
     * Get current url
     *
     * @return string
     */
    static function url(){

        $url = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
        $url .= '://'.$_SERVER['HTTP_HOST'];
        //$url .= in_array($_SERVER['SERVER_PORT'], array('80', '443')) ? '' : ':'.$_SERVER['SERVER_PORT'];
        $url .= $_SERVER['REQUEST_URI'];    

        return $url;
    }

    /**
     * JSON response
     *
     * @param boolean $success
     * @param array $data
     */
    static function response( $success, $data = array() ){
        if( $success ){
            wp_send_json_success( $data );
        }
        
        wp_send_json_error( $data );
    }

    /**
     * Verify nonce 
     *
     * @param string $success
     * @param string $data
     * @return boolean|die
     */
    static function is_valid_nonce( $nonce, $string ){

        if( ! wp_verify_nonce( $nonce, $string ) ){
            self::response( false, 'Invalid nonce' );
        }

        return true;
    }

    /**
     * Ajax: Render the block items       
     *
     * @param object $object
     */
    static function ajax_render( $object ){

        self::is_valid_nonce( $_POST['nonce'], 'sogrid_pagination' );

        // required params
        if( ! isset( $_POST['block_id'], $_POST['page'], $_POST['block_name'], $_POST['attrs'] ) ){
            self::response( false, 'Sogrid:: some params are missing' );
        }

        $attrs = stripslashes($_POST['attrs']);
        $attrs = json_decode( $attrs, true );

        // the query
        $query_data = array(
            'paged' => (int)$_POST['page'],
            'posts_per_page' => (int)$attrs['postsNumber'],
            'cat' => self::prepareQueryCategories( $attrs['categoriesSelected'] ),
        );
        
        // random & pagination solution
        $query_data = self::prepareRandomQuery( $attrs, $query_data );
        $query = self::query($query_data);

        $recent_posts = $query['posts'];

        if( ! count( $recent_posts ) ) {
            self::response( false, 'Sogrid:: no posts');
        }

        self::response( true, $object->renderItems( $recent_posts, $attrs ) );
    }

    /**
     * Prepare block attributes for ajax
     *
     * @return array
     */
    static function prepareAjaxAttributes( $attributes, $default ){

        $default = array_map(function( $value ){
            return $value['default'];
        }, $default );

        return wp_parse_args( $attributes, $default );
    }

    /**
     * Prepare random query
     *
     * @param array $attrs
     * @param array $query_data
     * @return array
     */
    static function prepareRandomQuery( $attrs, $query_data ){

        if( isset( $attrs['postsOrder'] ) && $attrs['postsOrder'] === 'rand' ){
            if( $attrs['isPaginationEnabled'] ){
                $rand_id = str_replace('sogrid-', '', $attrs['uid'] );
                $query_data['orderby'] = 'rand('.$rand_id.')';
            }
            else{
                $query_data['orderby'] = 'rand';
            }
        }

        return $query_data;
    }

    /**
     * Get posts
     *
     * @param array $attributes
     * @return array
     */
    static function query( $args ){
        
        $args = wp_parse_args($args, array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'post__not_in' => get_option( 'sticky_posts' ),
        ));

        $query = new WP_Query();

        return [
            'posts' => $query->query($args),
            'total_posts' => $query->found_posts,
            'max_pages' => $query->max_num_pages,
            'displayed_posts_count' => $query->post_count,
        ];  
    }

    /**
     * Render pagination
     *
     * @param array $attributes
     * @param array $query
     * @return string
     */
    static function renderPagination( $name, $attributes, $query ){

        global $post;

        if( ! $attributes['isPaginationEnabled'] ) return '';
        if( $attributes['paginationMaxPages'] == 0 ) return '';
        if( $query['max_pages'] < 2 ) return '';

        $max_pages = $attributes['paginationMaxPages'];

        if( $attributes['paginationMaxPages'] > $query['max_pages'] ){
            $max_pages = $query['max_pages'];
        }

        $output = '';

        for( $i=1; $i <= $max_pages; $i++ ){ 
            
            if( $i === 1 ){
                $output .= '<span data-num="'.$i.'" class="__active">'.$i.'</span>';
            }
            else{
                $output .= '<span data-num="'.$i.'">'.$i.'</span>';
            }
        }

        $post_id = 0;

        if( is_a( $post, 'WP_Post') ){
            $post_id = $post->ID;
        }

        return '<div 
                    class="sogrid__pagination __pos_'.$attributes['paginationPos'].'"
                    data-postid="'.$post_id.'"
                    data-blockname="'.$name.'"
                    data-blockid="'.$attributes['uid'].'"
                    data-nonce="'.wp_create_nonce('sogrid_pagination').'"
                >'.$output.'</div>';
    }
 
    /**
     * Returns a WP date fromat
     *
     * @param mixed $date
     * @return string
     */
    static function get_date( $date ){
		$date = new DateTime( $date );
        return $date->format( get_option('date_format') );
    }
 
    /**
     * Returns author data
     *
     * @param int $id
     * @return array
     */
    static function get_author( $id ){

        $user = get_userdata( $id );
        
        if( ! $user ){
            return false; 
        }

        $url = get_author_posts_url( $id );

        if( $user->first_name !== '' && $user->last_name !== '' ){
            return array(
                'name' => $user->first_name .' '. $user->last_name,
                'url' => $url 
            );
        }
        else{
            return array(
                'name' => $user->display_name,
                'url' => $url 
            );
		}
		
    }

    /**
     * Prepare query categories
     * @return void
     */
    static function prepareQueryCategories( $categories ){
        
        $ids = array_map(function( $cat ){
            return $cat['id'];
        }, $categories );   
        
        return implode( ',', $ids );
    }

    /**
     * Render Post Categories
     *
     * @param array $categories
     * @return string
     */
    static function renderPostCategories( $categories, $attributes ){

        if( ! $attributes['areCategoriesVisible'] ){
            return '';
        }

        $output = '';

        foreach ( $categories as $cat ) {
            $output .= '<a href="'.esc_url( get_category_link( $cat->term_id ) ).'">' . esc_html( $cat->name ) . '</a>';
        }
        
        return '<div class="sogrid__entry__categories">' .$output. '</div>';
    }

    /**
     * Render Post Excerpt
     *
     * @param object $post
     * @param array $attributes
     * @return string
     */
    static function renderPostExcerpt( $post, $attributes ){

        if( ! $attributes['isExcerptVisible'] ){
            return '';
        }
        
        $excerpt_max_words = apply_filters( 'sogrid_excerpt_max_words', (int)get_option('sogrid_settings_excerpt_max', 15 ) );
        
        return '<div class="sogrid__entry__excerpt">'.esc_html( wp_trim_words( get_the_excerpt( $post->ID ) , $excerpt_max_words, '...') ).'</div>';
    }
    
    /**
     * Renders Post Meta ( author, date ... )
     *
     * @param array $attributes
     * @return string
     */
    static function renderPostMeta( $post, $attributes ){

        $meta = '';

        if( $attributes['isAuthorVisible'] || $attributes['isDateVisible'] ){
            
            $meta = '<div class="sogrid__entry__meta">';
            
            if( $attributes['isAuthorVisible'] ){
                $author_data = Sogrid_Helpers::get_author( $post->post_author );
                $meta .= '<a class="sogrid__entry__author" href="'. esc_url( $author_data['url'] ).'">'.esc_html( $author_data['name'] ).'</a>';
            }
            
            if( $attributes['isDateVisible'] ){
                $meta .= '<span class="sogrid__entry__date">'.esc_html( get_the_date('', $post->ID) ).'</span>';
            }

            $meta .= '</div>';
            
        }

        return $meta;
    }

    /**
     * Renders Post Thumbnail
     *
     * @param int $post_id 
     * @param array $attributes
     * @return string
     */
    static function renderPostThumbnail( $post_id, $atts ){

        $class = 'sogrid__entry__thumbnail';

        if( isset($atts['hasBorderedImage']) && $atts['hasBorderedImage'] === true ){
            $class .= ' __bordered';
        }

        $attach_id = get_post_thumbnail_id( $post_id );

        if( $attach_id === '' ) return '';

        $src = wp_get_attachment_image_src( $attach_id, 'large' );

        if( ! is_array( $src ) ) return '';
        if( ! isset( $src[0] ) ) return '';

        return '
            <div class="'.$class.'">
                <img src="'.$src[0].'" alt="'.get_the_title( $post_id ).'" />
                '.Sogrid_Helpers::renderPostFormat( $post_id ).'
            </div>
        ';
    }

    /**
     * Renders Post Read More Button
     *
     * @param int $post_id 
     * @param array $attributes
     * @return string
     */
    static function renderPostReadMore( $post_id, $atts ){

        if( isset( $atts['isReadMoreVisible'] ) && ! $atts['isReadMoreVisible'] ) return '';

        $text = __('Read More', 'sogrid');

        if( isset( $atts['readMoreText'] ) ){
            $text = $atts['readMoreText'];
        }

        return '
            <a href="'.esc_url( get_the_permalink( $post_id ) ).'" class="sogrid__entry__readmore">
                '.$text.'
            </a>
        ';
    }

    /**
     * Renders Post Thumbnail For Pinterest
     *
     * @param array $attributes
     * @return string
     */
    static function renderPinterestPostThumbnail( $post_id ){
        return '
        <div class="sogrid__entry__thumbnail_wrapper">
            <div class="sogrid__entry__thumbnail">
                '.get_the_post_thumbnail( $post_id ).'
                '.Sogrid_Helpers::renderPostFormat( $post_id ).'
            </div>
        </div>
    ';
    }

    /**
     * Render post format
     * @param {string} format 
     */
    static function renderPostFormat( $post_id ){

        $format = get_post_format($post_id);
        $link = get_the_permalink($post_id);

        if( $format === 'video' ){
            $icon = self::IconVideoFormat();
        }

        else if( $format === 'audio' ){
            $icon = self::IconAudioFormat();
        }

        else if( $format === 'gallery' ){
            $icon = self::IconGalleryFormat();
        }

        else if( $format === 'image' ){
            $icon = self::IconImageFormat();
        }

        else if( $format === 'link' ){
            $icon = self::IconLinkFormat();
        }

        else if( $format === 'quote' ){
            $icon = self::IconQuoteFormat();
        }

        else if( $format === 'chat' ){
            $icon = self::IconChatFormat();
        }

        else{
            $icon = self::IconStandardFormat();
        }

        return '
            <a class="sogrid__entry__format" data-format="'.esc_attr( $format ).'" href="'.esc_url( get_the_permalink($post_id) ).'">
                <span>'.$icon.'</span>
            </a>
        ';
        
    }

    static function IconVideoFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
            </svg>';   
    }
    
    static function IconAudioFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M5 17h-5v-10h5v10zm2-10v10l9 5v-20l-9 5zm11.008 2.093c.742.743 1.2 1.77 1.198 2.903-.002 1.133-.462 2.158-1.205 2.9l1.219 1.223c1.057-1.053 1.712-2.511 1.715-4.121.002-1.611-.648-3.068-1.702-4.125l-1.225 1.22zm2.142-2.135c1.288 1.292 2.082 3.073 2.079 5.041s-.804 3.75-2.096 5.039l1.25 1.254c1.612-1.608 2.613-3.834 2.616-6.291.005-2.457-.986-4.681-2.595-6.293l-1.254 1.25z"/>
            </svg>';   
    }
    
    static function IconGalleryFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M9 12c0-.552.448-1 1.001-1s.999.448.999 1-.446 1-.999 1-1.001-.448-1.001-1zm6.2 0l-1.7 2.6-1.3-1.6-3.2 4h10l-3.8-5zm8.8-5v14h-20v-3h-4v-15h21v4h3zm-20 9v-9h15v-2h-17v11h2zm18-7h-16v10h16v-10z"/>
            </svg>';   
    }
    
    static function IconImageFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M5 8.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5zm9 .5l-2.519 4-2.481-1.96-4 5.96h14l-5-8zm8-4v14h-20v-14h20zm2-2h-24v18h24v-18z"/>
            </svg>';   
    }
    
    static function IconLinkFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M6.188 8.719c.439-.439.926-.801 1.444-1.087 2.887-1.591 6.589-.745 8.445 2.069l-2.246 2.245c-.644-1.469-2.243-2.305-3.834-1.949-.599.134-1.168.433-1.633.898l-4.304 4.306c-1.307 1.307-1.307 3.433 0 4.74 1.307 1.307 3.433 1.307 4.74 0l1.327-1.327c1.207.479 2.501.67 3.779.575l-2.929 2.929c-2.511 2.511-6.582 2.511-9.093 0s-2.511-6.582 0-9.093l4.304-4.306zm6.836-6.836l-2.929 2.929c1.277-.096 2.572.096 3.779.574l1.326-1.326c1.307-1.307 3.433-1.307 4.74 0 1.307 1.307 1.307 3.433 0 4.74l-4.305 4.305c-1.311 1.311-3.44 1.3-4.74 0-.303-.303-.564-.68-.727-1.051l-2.246 2.245c.236.358.481.667.796.982.812.812 1.846 1.417 3.036 1.704 1.542.371 3.194.166 4.613-.617.518-.286 1.005-.648 1.444-1.087l4.304-4.305c2.512-2.511 2.512-6.582.001-9.093-2.511-2.51-6.581-2.51-9.092 0z"/>
            </svg>';   
    }
    
    static function IconChatFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M12 1c-6.338 0-12 4.226-12 10.007 0 2.05.739 4.063 2.047 5.625.055 1.83-1.023 4.456-1.993 6.368 2.602-.47 6.301-1.508 7.978-2.536 9.236 2.247 15.968-3.405 15.968-9.457 0-5.812-5.701-10.007-12-10.007zm-5 11.5c-.829 0-1.5-.671-1.5-1.5s.671-1.5 1.5-1.5 1.5.671 1.5 1.5-.671 1.5-1.5 1.5zm5 0c-.829 0-1.5-.671-1.5-1.5s.671-1.5 1.5-1.5 1.5.671 1.5 1.5-.671 1.5-1.5 1.5zm5 0c-.828 0-1.5-.671-1.5-1.5s.672-1.5 1.5-1.5c.829 0 1.5.671 1.5 1.5s-.671 1.5-1.5 1.5z"/>
            </svg>';   
    }
    
    static function IconQuoteFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M13 14.725c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275zm-13 0c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275z"/>
            </svg>';   
    }
    
    static function IconStandardFormat(){
        return '
            <svg class="dashicon" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                <path d="M13.744 8s1.522-8-3.335-8h-8.409v24h20v-13c0-3.419-5.247-3.745-8.256-3zm4.256 11h-12v-1h12v1zm0-3h-12v-1h12v1zm0-3h-12v-1h12v1zm-3.432-12.925c2.202 1.174 5.938 4.883 7.432 6.881-1.286-.9-4.044-1.657-6.091-1.179.222-1.468-.185-4.534-1.341-5.702z"/>
            </svg>';   
    }
    
}
