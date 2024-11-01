const { 
    RangeControl,
    TextControl,
    PanelBody,
    TabPanel,
} = wp.components;

const {
    Fragment,
} = wp.element;

const { __ } = wp.i18n;

const PaddingMarginSettings = ({ attributes, setAttributes }) => {

    return (

        <PanelBody
            title={ __('Padding/Margin Settings', 'sogrid') }
            initialOpen={ false }
        >

            {attributes.hasOwnProperty('marginTop') &&(
                <RangeControl
                    label = { __( 'Margin Top', 'sogrid' ) }
                    value = { attributes.marginTop }
                    min = { 0 }
                    max = { 500 }
                    step = { 1 }
                    onChange = { marginTop => setAttributes({ marginTop }) } 
                />  
            )}

            {attributes.hasOwnProperty('marginBottom') &&(
                <RangeControl
                    label = { __( 'Margin Bottom', 'sogrid' ) }
                    value = { attributes.marginBottom }
                    min = { 0 }
                    max = { 500 }
                    step = { 1 }
                    onChange = { marginBottom => setAttributes({ marginBottom }) } 
                />  
            )}

            {attributes.hasOwnProperty('paddingTop') &&(
                <RangeControl
                    label = { __( 'Padding Top', 'sogrid' ) }
                    value = { attributes.paddingTop }
                    min = { 0 }
                    max = { 500 }
                    step = { 1 }
                    onChange = { paddingTop => setAttributes({ paddingTop }) } 
                />  
            )}

            {attributes.hasOwnProperty('paddingBottom') &&(
                <RangeControl
                    label = { __( 'Padding Bottom', 'sogrid' ) }
                    value = { attributes.paddingBottom }
                    min = { 0 }
                    max = { 500 }
                    step = { 1 }
                    onChange = { paddingBottom => setAttributes({ paddingBottom }) } 
                />  
            )}

            {attributes.hasOwnProperty('paddingLeft') &&(
                <RangeControl
                    label = { __( 'Padding Left', 'sogrid' ) }
                    value = { attributes.paddingLeft }
                    min = { 0 }
                    max = { 500 }
                    step = { 1 }
                    onChange = { paddingLeft => setAttributes({ paddingLeft }) } 
                />  
            )}

            {attributes.hasOwnProperty('paddingRight') &&(
                <RangeControl
                    label = { __( 'Padding Right', 'sogrid' ) }
                    value = { attributes.paddingRight }
                    min = { 0 }
                    max = { 500 }
                    step = { 1 }
                    onChange = { paddingRight => setAttributes({ paddingRight }) } 
                />  
            )}

        </PanelBody>

    );

}

export default PaddingMarginSettings;
