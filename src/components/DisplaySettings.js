const { 
    PanelBody,
    ToggleControl,
    TextControl,
} = wp.components;

const { __ } = wp.i18n;

const DisplaySettings = ({ attributes, setAttributes }) => {
    
    return (
        <PanelBody
            title={ __('Display Settings', 'sogrid') }
            initialOpen={ false }
        >
            
            {attributes.hasOwnProperty('hasBorderedImage') &&(
                <ToggleControl
                    label = { __("Bordered Thumbnail?", 'sogrid') }
                    checked = { attributes.hasBorderedImage }
                    onChange = { hasBorderedImage => setAttributes({ hasBorderedImage }) }
                />
            )}

            {attributes.hasOwnProperty('isAuthorVisible') &&(
                <ToggleControl
                    label = { __("Display Author?", 'sogrid') }
                    checked = { attributes.isAuthorVisible }
                    onChange = { isAuthorVisible => setAttributes({ isAuthorVisible }) }
                />
            )}

            {attributes.hasOwnProperty('isDateVisible') &&(
                <ToggleControl
                    label = { __("Display Date?", 'sogrid') }
                    checked = { attributes.isDateVisible }
                    onChange = { isDateVisible => setAttributes({ isDateVisible }) }
                />
            )}

            {attributes.hasOwnProperty('isExcerptVisible') &&(
                <ToggleControl
                    label = { __("Display Excerpt?", 'sogrid') }
                    checked = { attributes.isExcerptVisible }
                    onChange = { isExcerptVisible => setAttributes({ isExcerptVisible }) }
                />
            )}

            {attributes.hasOwnProperty('areCategoriesVisible') &&(
                <ToggleControl
                    label = { __("Display Categories?", 'sogrid') }
                    checked = { attributes.areCategoriesVisible }
                    onChange = { areCategoriesVisible => setAttributes({ areCategoriesVisible }) }
                />
            )}

            {attributes.hasOwnProperty('isReadMoreVisible') &&(
                <ToggleControl
                    label = { __("Display Read More Button?", 'sogrid') }
                    checked = { attributes.isReadMoreVisible }
                    onChange = { isReadMoreVisible => setAttributes({ isReadMoreVisible }) }
                />
            )}

            {attributes.hasOwnProperty('readMoreText') &&(
                <TextControl
                    label = { __("Read More Button Text", 'sogrid') }
                    value = { attributes.readMoreText }
                    onChange = { readMoreText => setAttributes({ readMoreText }) }
                />
            )}

        </PanelBody>

    );

}

export default DisplaySettings;
