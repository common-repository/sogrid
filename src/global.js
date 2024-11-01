import { 
    IconVideoFormat, 
    IconAudioFormat, 
    IconGalleryFormat, 
    IconImageFormat, 
    IconLinkFormat, 
    IconQuoteFormat, 
    IconChatFormat, 
    IconStandardFormat 
} from './icons';

/**
 * Colors
 */
export const colorList = [
    { color: '#F9583B', name: 'GPB Color' },
    { color: '#e84393', name : 'Prunus Avium' },
    { color: '#d63031', name : 'Chi-gong' },
    { color: '#fd79a8', name: 'Pico-8' },
    { color: '#00cec9', name : 'Robin\'s Egg Blue' },
    { color: '#e17055', name : 'Orange Ville' },
    { color: '#fdcb6e', name : 'Bright Yarrow' },
    { color: '#55efc4', name : 'Light Greenish Blue' },
    { color: '#00b894', name : 'Mint Leaf' },
    { color: '#6c5ce7', name : 'Exodus Fruit' },
    { color: '#ffeaa7', name : 'Sour Lemon' },
    { color: '#fab1a0', name : 'First Date' },
    { color: '#74b9ff', name : 'Green Darnet Tail' },
    { color: '#a29bfe', name : 'Sky Moment' },
    { color: '#2d3436', name : 'Dracula Orchid' },
    { color: '#dfe6e9', name : 'City Lights' },
    { color: '#636e72', name : 'American River' },	
];

/**
 * Fetch posts using rest
 * @param {function} select 
 * @param {object} props 
 */
export function getPosts( select, props ){

    const REST = select( 'core' ).getEntityRecords;

    const { 
        attributes,
    } = props;

    const {
        postsNumber,
        categoriesSelected,
        postsOrder
    } = attributes;
            
    // more info https://developer.wordpress.org/rest-api/reference/posts/
    const postsQueryArgs = {
        per_page: postsNumber,
        orderby: postsOrder === 'rand' ? 'date' : postsOrder, // doesnt support random yet
        categories: categoriesSelected ? categoriesSelected.map( cat => cat.id ) : [],
    };

    // more info https://developer.wordpress.org/rest-api/reference/categories/
    const catsQueryArgs = {
        per_page: 100,
        hide_empty: 1,
    };
    
    return {
        posts: REST( 'postType', 'post', postsQueryArgs ),
        categories: REST( 'taxonomy', 'category', catsQueryArgs ),        
    };   
}


/**
 * Creates unique html id
 */
export function generateID( uid, setAttributes ){

    console.log(uid);
    
    const newId = 'sogrid-' + new Date().getTime();

    // fix for duplicating a block ( same uid )
    if( uid !== '' ){
        const isFound = document.querySelectorAll( '#' + uid );

        if( isFound.length > 1 ){
            setAttributes({ uid : newId });
        }
    }
    // create id
    else {
        setAttributes({ uid : newId });
    }
}

/**
 * Renders post title
 */
export function renderPostTitle(title){

    return (
        <h3 className="sogrid__entry__title">
            <a href="#">{title}</a>
        </h3>
    );
}

/**
 * Renders post read more
 */
export function renderPostReadMore( attributes ){

    if( ! attributes.isReadMoreVisible ){
        return null;
    }

    if( ! attributes.readMoreText ){
        return null;
    }

    return (
        <a href="#" className="sogrid__entry__readmore">{attributes.readMoreText}</a>
    );
}

/**
 * Renders pagination
 */
export function renderPagination( attributes ){

    if( ! attributes.isPaginationEnabled ) return null;

    let pages = [];

    for (let index = 0; index < attributes.paginationMaxPages; index++) {
        pages.push(<span className={ index === 0 ? '__active' : '' } onClick={ () => alert('Only on the frontend') }>{index + 1}</span>);
    }

    return (
        <div className={ "sogrid__pagination __pos_" + attributes.paginationPos }>
            {pages}
        </div>
    );
}

/**
 * Renders post excerpt
 * @param {string} excerpt
 * @param {boolean} isVisible
 */
export function renderPostExcerpt( excerpt, isVisible ){

    if( ! isVisible ) return null;
    if( ! excerpt ) return null;

    return (
        <p className="sogrid__entry__excerpt">{excerpt}</p>
    );
}

/**
 * Renders post meta
 * @param {object} post 
 * @param {object} attributes 
 */
export function renderPostMeta( post, attributes ) {
    
    if( ! attributes.isAuthorVisible && ! attributes.isDateVisible ){
        return null;
    }

    return (
        <div className="sogrid__entry__meta">
            { attributes.isAuthorVisible &&(
                <a href="#" className="sogrid__entry__author">{post.author_data.name}</a>
            )}

            { attributes.isDateVisible &&(
                <span className="sogrid__entry__date">{post.date_formated}</span>
            )}
        </div>
    );
}

/**
 * Renders post Categories
 * @param {object} categories 
 * @param {boolean} areVisible
 */
export function renderCategories( categories, areVisible ){

    if( ! areVisible ){
        return null;
    }

    const cats = Object.values( categories );

    if( ! cats.length ){
        return null;
    }

    return(
        <div className="sogrid__entry__categories">
            {cats.map( cat => (
                <a key={cat.term_id} href="#">{cat.cat_name}</a>
            ))}
        </div>
    );
}

/**
 * Renders post thumbnail
 * @param {string} format 
 */
export function renderPostThumbnail( post, hasBorderedImage ){

    if( ! post.featured_image_src ){
        return null;
    }
    
    return (
        <div className={"sogrid__entry__thumbnail" + (hasBorderedImage ? ' __bordered' : '')}>
            <img src={post.featured_image_src} alt={post.title.raw} />

            {renderPostFormat(post.format)}
        </div>
    );
}

/**
 * Renders post format
 * @param {string} format 
 */
export function renderPostFormat( format ){

    let icon;

    if( format === 'video' ){
        icon = <IconVideoFormat />
    }

    else if( format === 'audio' ){
        icon = <IconAudioFormat />
    }

    else if( format === 'gallery' ){
        icon = <IconGalleryFormat />
    }

    else if( format === 'image' ){
        icon = <IconImageFormat />
    }

    else if( format === 'link' ){
        icon = <IconLinkFormat />
    }

    else if( format === 'quote' ){
        icon = <IconQuoteFormat />
    }

    else if( format === 'chat' ){
        icon = <IconChatFormat />
    }

    else{
        icon = <IconStandardFormat />
    }

    return (
        <a className="sogrid__entry__format" data-format={format} href="#">
            <span>{icon}</span>
        </a>
    )
    
}
