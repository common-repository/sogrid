import {getPosts} from './../../global';
import { IconGridBlock } from '../../icons';
import Edit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;

registerBlockType( 'sogrid/pinterest', {
	
	title: __( 'Pinterest Grid - Sogrid', 'sogrid' ), 
	
	icon: IconGridBlock, 
	
	category: 'sogrid', 

	keywords: [
		__( 'pinterest', 'sogrid' ),
		__( 'grid', 'sogrid' ),
		__( 'posts', 'sogrid' ),
    ],
    
    supports: {
        html:false,
    },

    edit: withSelect( getPosts ) ( Edit ),

    save: () => null,

});
