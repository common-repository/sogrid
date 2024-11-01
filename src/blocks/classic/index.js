import { getPosts} from './../../global';
import { IconClassicBlock } from '../../icons';
import Edit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { withSelect } = wp.data;

registerBlockType( 'sogrid/classic', {
	
	title: __( 'Classic - Sogrid', 'sogrid' ), 
	
	icon: IconClassicBlock, 
	
	category: 'sogrid', 

	keywords: [
		__( 'classic', 'sogrid' ),
		__( 'grid', 'sogrid' ),
		__( 'posts', 'sogrid' ),
    ],
    
    supports: {
        html:false,
    },

    edit: withSelect( getPosts ) ( Edit ),

    save: () => null,

});
