import {getPosts} from './../../global';
import { IconGridBlock } from '../../icons';
import Edit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;

registerBlockType( 'sogrid/normal', {
	
	title: __( 'Normal Grid - Sogrid', 'sogrid' ), 
	
	icon: IconGridBlock, 
	
	category: 'sogrid', 

	keywords: [
		__( 'normal', 'sogrid' ),
		__( 'grid', 'sogrid' ),
		__( 'posts', 'sogrid' ),
    ],
    
    supports: {
        html:false,
    },

    edit: withSelect( getPosts ) ( Edit ),

    save: () => null,

});
