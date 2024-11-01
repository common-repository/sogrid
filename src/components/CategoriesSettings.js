import SearchListControl from './SearchList';

const { 
    PanelBody,
    RangeControl,
    SelectControl,
} = wp.components;

const { __ } = wp.i18n;

const CategoriesSettings = ({ attributes, categories, setAttributes }) => {

    return (

        <PanelBody
            title={ __('Posts & Categories', 'sogrid') }
            initialOpen={ true }
        >

            <RangeControl
                label = { __( 'Number of Posts', 'sogrid' ) }
                value = { attributes.postsNumber }
                min = { 1 }
                max = { 100 }
                step = { 1 }
                onChange = { postsNumber => setAttributes({ postsNumber }) } 
            />

            {attributes.hasOwnProperty('readmoreColor') &&(
                <SelectControl
                    label = { __( 'Posts Order', 'sogrid' ) }
                    value = { attributes.postsOrder }
                    options={ [
                        { label: __('Latest', 'sogrid'), value: 'date' },
                        { label: __('Author', 'sogrid'), value: 'author' },
                        { label: __('Modified', 'sogrid'), value: 'modified' },
                        { label: __('Title', 'sogrid'), value: 'title' },
                        { label: __('Random (Only Frontend)', 'sogrid'), value: 'rand' },
                    ] }
                    onChange = { postsOrder => setAttributes({ postsOrder }) } 
                />
            )}

            <SearchListControl 
                label={ __('Select Categories', 'sogrid') }
                className={ "sogrid-categories" }
                list = { categories ? categories : [] } 
                selected = { attributes.categoriesSelected ? attributes.categoriesSelected : [] }
                onChange= { ( value = [] ) => setAttributes({ categoriesSelected: value }) }
            ></SearchListControl>

        </PanelBody>
        
    );

}

export default CategoriesSettings;
