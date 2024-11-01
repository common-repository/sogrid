import { getPosts} from './../../global';
import { IconGridBlock } from '../../icons';
import Edit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;

registerBlockType( 'sogrid/oos2', {
	
	title: __( 'One & Others - Style 2 - Sogrid', 'sogrid' ), 
	
	icon: IconGridBlock, 
	
	category: 'sogrid', 

	keywords: [
		__( 'one', 'sogrid' ),
		__( 'others', 'sogrid' ),
		__( 'posts', 'sogrid' ),
    ],
    
    supports: {
        html:false,
    },

    edit: withSelect( getPosts ) ( Edit ),

    save: () => null,

});
