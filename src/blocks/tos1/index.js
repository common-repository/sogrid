import { getPosts } from './../../global';
import { IconGridBlock } from '../../icons';
import Edit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;

registerBlockType( 'sogrid/tos1', {
	
	title: __( 'Two & Others - Sogrid', 'sogrid' ), 
	
	icon: IconGridBlock, 
	
	category: 'sogrid', 

	keywords: [
		__( 'two', 'sogrid' ),
		__( 'others', 'sogrid' ),
		__( 'posts', 'sogrid' ),
    ],
    
    supports: {
        html:false,
    },

    edit: withSelect( getPosts ) ( Edit ),

    save: () => null,

});
