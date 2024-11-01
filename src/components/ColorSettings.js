import ColorControl from './ColorControl';

const { 
    PanelBody,
} = wp.components;

const { __ } = wp.i18n;

const ColorSettings = ({ attributes, setAttributes }) => {

    return (

        <PanelBody
            title={ __('Color Settings', 'sogrid') }
            initialOpen={ false }
        >

            {attributes.hasOwnProperty('backgroundColor') &&(
                <ColorControl
                    label = { __('Background', 'sogrid') }
                    value = { attributes.backgroundColor }
                    onChange={ ( backgroundColor ) => setAttributes({ backgroundColor }) } 
                />
            )}

            {attributes.hasOwnProperty('borderColor') &&(
                <ColorControl
                    label = { __('Border', 'sogrid') }
                    value = { attributes.borderColor }
                    onChange={ ( borderColor ) => setAttributes({ borderColor }) } 
                />
            )}

            {attributes.hasOwnProperty('bottomBorderColor') &&(
                <ColorControl
                    label = { __('Bottom Border', 'sogrid') }
                    value = { attributes.bottomBorderColor }
                    onChange={ ( bottomBorderColor ) => setAttributes({ bottomBorderColor }) } 
                />
            )}

            <ColorControl
                label = { __('Title', 'sogrid') }
                value = { attributes.titleColor }
                onChange={ ( titleColor ) => setAttributes({ titleColor }) } 
            />

            <ColorControl
                label = { __('Author', 'sogrid') }
                value = { attributes.authorColor }
                onChange={ ( authorColor ) => setAttributes({ authorColor }) } 
            />

            <ColorControl
                label = { __('Date', 'sogrid') }
                value = { attributes.dateColor }
                onChange={ ( dateColor ) => setAttributes({ dateColor }) } 
            />

            <ColorControl
                label = { __('Categories', 'sogrid') }
                value = { attributes.categoryColor }
                onChange={ ( categoryColor ) => setAttributes({ categoryColor }) } 
            />

            <ColorControl
                label = { __('Excerpt', 'sogrid') }
                value = { attributes.excerptColor }
                onChange={ ( excerptColor ) => setAttributes({ excerptColor }) } 
            />

            {attributes.hasOwnProperty('readmoreColor') &&(
                <ColorControl
                    label = { __('Read More Button', 'sogrid') }
                    value = { attributes.readmoreColor }
                    onChange={ ( readmoreColor ) => setAttributes({ readmoreColor }) } 
                />
            )}

            {attributes.hasOwnProperty('readmoreBGColor') &&(
                <ColorControl
                    label = { __('Read More Button Background', 'sogrid') }
                    value = { attributes.readmoreBGColor }
                    onChange={ ( readmoreBGColor ) => setAttributes({ readmoreBGColor }) } 
                />
            )}

        </PanelBody>

    );

}

export default ColorSettings;
