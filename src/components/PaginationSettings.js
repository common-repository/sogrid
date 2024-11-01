import ColorControl from './ColorControl';

const { 
    PanelBody,
    ToggleControl,
    RangeControl,
    SelectControl,
} = wp.components;

const { __ } = wp.i18n;

const PaginationSettings = ({ attributes, setAttributes }) => {
    
    return (
        <PanelBody
            title={ __('Pagination Settings', 'sogrid') }
            initialOpen={ false }
        >
            
            {attributes.hasOwnProperty('isPaginationEnabled') &&(
                <ToggleControl
                    label = { __("Enable Pagination", 'sogrid') }
                    help = { attributes.isPaginationEnabled ? __("Enabled", 'sogrid') : __("Disabled", 'sogrid') }
                    checked = { attributes.isPaginationEnabled }
                    onChange = { isPaginationEnabled => setAttributes({ isPaginationEnabled }) }
                />
            )}

            {attributes.hasOwnProperty('paginationMaxPages') &&(
                <RangeControl
                    label = { __("Max Pages", 'sogrid') }
                    value = { attributes.paginationMaxPages }
                    onChange = { paginationMaxPages => setAttributes({ paginationMaxPages }) }
                    min={ 1 }
                    max={ 100 }
                />
            )}

            {attributes.hasOwnProperty('paginationPos') &&(
                <SelectControl
                    label = { __("Position", 'sogrid') }
                    value = { attributes.paginationPos }
                    onChange = { paginationPos => setAttributes({ paginationPos }) }
                    options={ [
                        { label: __('Top', 'sogrid'), value: 'top' },
                        { label: __('Bottom', 'sogrid'), value: 'bottom' },
                    ] }
                />
            )}

            {attributes.hasOwnProperty('paginationColor') &&(
                <ColorControl
                    label = { __('Numbers Color', 'sogrid') }
                    value = { attributes.paginationColor }
                    onChange={ ( paginationColor ) => setAttributes({ paginationColor }) } 
                />
            )}

            {attributes.hasOwnProperty('paginationBgColor') &&(
                <ColorControl
                    label = { __('Numbers Background Color', 'sogrid') }
                    value = { attributes.paginationBgColor }
                    onChange={ ( paginationBgColor ) => setAttributes({ paginationBgColor }) } 
                />
            )}

            {attributes.hasOwnProperty('paginationActiveColor') &&(
                <ColorControl
                    label = { __('Active Number Color', 'sogrid') }
                    value = { attributes.paginationActiveColor }
                    onChange={ ( paginationActiveColor ) => setAttributes({ paginationActiveColor }) } 
                />
            )}

            {attributes.hasOwnProperty('paginationActiveBgColor') &&(
                <ColorControl
                    label = { __('Active Number Background Color', 'sogrid') }
                    value = { attributes.paginationActiveBgColor }
                    onChange={ ( paginationActiveBgColor ) => setAttributes({ paginationActiveBgColor }) } 
                />
            )}
            
        </PanelBody>

    );

}

export default PaginationSettings;
