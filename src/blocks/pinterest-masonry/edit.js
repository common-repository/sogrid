import { 
    generateID, 
    renderPostExcerpt, 
    renderCategories, 
    renderPostFormat, 
    renderPostMeta, 
    renderPostTitle,
    renderPagination,
    renderPostReadMore
} from '../../global';
import ResponsiveSettings from '../../components/ResponsiveSettings';
import DisplaySettings from '../../components/DisplaySettings';
import ColorSettings from '../../components/ColorSettings';
import CategoriesSettings from '../../components/CategoriesSettings';
import PaddingMarginSettings from '../../components/PaddingMarginSettings';
import PaginationSettings from '../../components/PaginationSettings';

const {
    InspectorControls,
} = wp.blockEditor;

const {
    Fragment,
    Component
} = wp.element;

const { __ } = wp.i18n;

function renderStyle( attributes ){

    const {
        uid,
        fontSize,
        mobileFontSize,
        tabletFontSize,
        titleColor,
        categoryColor,
        excerptColor,
        dateColor,
        authorColor,
        borderColor,
        paddingTop,
        paddingBottom,
        paddingLeft,
        paddingRight,
        marginTop,
        marginBottom,
        readmoreColor,
        readmoreBGColor,
    } = attributes;

    let output = `
        #${uid}.sogrid--pinterest-masonry{
            font-size: ${mobileFontSize}px;
            padding: ${paddingTop}px ${paddingRight}px ${paddingBottom}px ${paddingLeft}px;
            margin-top: ${marginTop}px;
            margin-bottom: ${marginBottom}px;
            background-color: ${attributes.backgroundColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__content{
            border-color: ${borderColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__title a{
            color: ${titleColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__meta{
            color: ${dateColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__meta .sogrid__entry__author{
            color: ${authorColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__categories a{
            color: ${categoryColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__excerpt{
            color: ${excerptColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__entry__readmore{
            color: ${readmoreColor};
            background-color: ${readmoreBGColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__pagination span{
            color: ${attributes.paginationColor};
            background-color: ${attributes.paginationBgColor};
        }

        #${uid}.sogrid--pinterest-masonry .sogrid__pagination span.__active{
            color: ${attributes.paginationActiveColor};
            background-color: ${attributes.paginationActiveBgColor};
        }

        @media all and (min-width: 768px) {
            #${uid}.sogrid--pinterest-masonry{
                font-size: ${tabletFontSize}px;
            }
        }

        @media all and (min-width: 992px) {
            #${uid}.sogrid--pinterest-masonry{
                font-size: ${fontSize}px;
            }
        }
    `;

    return output;
}

class Edit extends  Component{

    constructor(...args){
        super(...args);

        this.renderPost = this.renderPost.bind(this);
    }

    componentDidMount(){
        generateID( this.props.attributes.uid, this.props.setAttributes );
    }

    renderPost( post ){

        const { attributes } = this.props;

        const {
            areCategoriesVisible,
            isExcerptVisible
        } = attributes;

        return (
            <article key={post.id} className="sogrid__entry">
                <div className="sogrid__entry__wrapper">

                    {post.featured_image_src && (
                        <div className="sogrid__entry__thumbnail_wrapper">
                            <a className="sogrid__entry__thumbnail" href="#">
                                <img src={post.featured_image_src} alt={post.title.raw} />
                                {renderPostFormat(post.format)}
                            </a>
                        </div>
                    )}

                    <div className="sogrid__entry__content">

                        {renderCategories(post.categories_data, areCategoriesVisible)}

                        {renderPostTitle(post.title.raw)}
            
                        {renderPostMeta(post, attributes)}
                        
                        {renderPostExcerpt(post.excerpt_data, isExcerptVisible)}
            
                        { renderPostReadMore( attributes ) }

                    </div>

                </div>
            </article>
        )
        
    }

    render(){

        const {
            posts,
            categories,
            attributes,
            setAttributes,
            isSelected,
        } = this.props;

        const {
            columns,
            tabletColumns,
            mobileColumns,
        } = attributes;

        if( ! posts ){
            return "loading !";
        }

        if ( posts.length === 0 ) {
            return "No posts";
        }

        const output = posts.map( post => this.renderPost( post ) );
        const paginationOutput = renderPagination(attributes);

        return [

            isSelected && (

                <InspectorControls> 

                    <CategoriesSettings attributes={attributes} categories={categories} setAttributes={setAttributes} />

                    <ResponsiveSettings attributes={attributes} setAttributes={setAttributes} />

                    <PaginationSettings attributes={attributes} setAttributes={setAttributes} />

                    <DisplaySettings attributes={attributes} setAttributes={setAttributes} />

                    <PaddingMarginSettings attributes={attributes} setAttributes={setAttributes} />

                    <ColorSettings attributes={attributes} setAttributes={setAttributes} />                    

                </InspectorControls>
            ),

            <Fragment>
                
                <style>{renderStyle(attributes)}</style>

                <div 
                    id = { attributes.uid }
                    className = { 'sogrid sogrid--pinterest-masonry' }
                    data-desktop = { columns }
                    data-tablet = { tabletColumns }
                    data-mobile = { mobileColumns }
                >
                    { attributes.paginationPos === 'top' && paginationOutput }

                    <div className="sogrid__posts">
                        {output}
                    </div>

                    { attributes.paginationPos === 'bottom' && paginationOutput }

                </div>
                
            </Fragment>
        ]

    }
    
}

export default Edit;
