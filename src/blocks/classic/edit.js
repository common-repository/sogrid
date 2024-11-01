import { generateID, renderPostExcerpt, renderCategories, renderPostThumbnail, renderPostTitle, renderPostMeta, renderPagination, renderPostReadMore } from '../../global';
import ResponsiveSettings from '../../components/ResponsiveSettings';
import PaginationSettings from '../../components/PaginationSettings';
import PaddingMarginSettings from '../../components/PaddingMarginSettings';
import DisplaySettings from '../../components/DisplaySettings';
import ColorSettings from '../../components/ColorSettings';
import CategoriesSettings from '../../components/CategoriesSettings';

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
        readmoreColor,
        readmoreBGColor,
        bottomBorderColor,
        dateColor,
        authorColor,
        paddingTop,
        paddingBottom,
        paddingLeft,
        paddingRight,
        marginTop,
        marginBottom,
    } = attributes;

    let output = `
        #${uid}.sogrid--classic{
            font-size: ${mobileFontSize}px;
            padding: ${paddingTop}px ${paddingRight}px ${paddingBottom}px ${paddingLeft}px;
            margin-top: ${marginTop}px;
            margin-bottom: ${marginBottom}px;
            background-color: ${attributes.backgroundColor};
        }

        #${uid}.sogrid--classic .sogrid__entry{
            border-color: ${bottomBorderColor};
        }

        #${uid}.sogrid--classic .sogrid__entry__title a{
            color: ${titleColor};
        }

        #${uid}.sogrid--classic .sogrid__entry__meta{
            color: ${dateColor};
        }

        #${uid}.sogrid--classic .sogrid__entry__meta .sogrid__entry__author{
            color: ${authorColor};
        }

        #${uid}.sogrid--classic .sogrid__entry__categories a{
            color: ${categoryColor};
        }

        #${uid}.sogrid--classic .sogrid__entry__excerpt{
            color: ${excerptColor};
        }

        #${uid}.sogrid--classic .sogrid__entry__readmore{
            color: ${readmoreColor};
            background-color: ${readmoreBGColor};
        }
        
        #${uid}.sogrid--classic .sogrid__pagination span{
            color: ${attributes.paginationColor};
            background-color: ${attributes.paginationBgColor};
        }

        #${uid}.sogrid--classic .sogrid__pagination span.__active{
            color: ${attributes.paginationActiveColor};
            background-color: ${attributes.paginationActiveBgColor};
        }
        
        @media all and (min-width: 768px) {
            #${uid}.sogrid--classic{
                font-size: ${tabletFontSize}px;
            }
        }

        @media all and (min-width: 992px) {
            #${uid}.sogrid--classic{
                font-size: ${fontSize}px;
            }
        }
    `;

    return output;
}

class Edit extends Component{

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
            hasBorderedImage,
            isExcerptVisible,
        } = attributes;

        return (
            <article key={post.id} className="sogrid__entry">
                
                {renderPostThumbnail( post, hasBorderedImage)}
    
                <div className="sogrid__entry__content">

                    {renderCategories(post.categories_data, areCategoriesVisible)}

                    {renderPostTitle(post.title.raw)}
    
                    {renderPostMeta(post, attributes)}

                    {renderPostExcerpt(post.excerpt_data, isExcerptVisible)}

                    { renderPostReadMore( attributes ) }

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
                    className = { 'sogrid sogrid--classic' }
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
