/**
 * Webpack Configuration :: Production
 */

const paths = require( './paths' );
const webpack = require( 'webpack' );
const externals = require( './externals' );
const autoprefixer = require( 'autoprefixer' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );

// Source maps are resource heavy and can cause out of memory issue for large source files.
const shouldUseSourceMap = process.env.GENERATE_SOURCEMAP === 'true';

// Export configuration.
module.exports = {
	entry: {
		'./dist/blocks.build': paths.pluginBlocksJs, // 'name' : 'path/file.ext'.
	},
	output: {
		// Add /* filename */ comments to generated require()s in the output.
		pathinfo: true,
		// The dist folder.
		path: paths.pluginDist,
		filename: '[name].js', // [name] = './dist/blocks.build' as defined above.
	},
	// You may want 'eval' instead if you prefer to see the compiled output in DevTools.
	devtool: shouldUseSourceMap ? 'source-map' : false,
	module: {
		rules: [
			{
				test: /\.(js|jsx|mjs)$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader',
					options: {
						
						// This is a feature of `babel-loader` for webpack (not Babel itself).
						// It enables caching results in ./node_modules/.cache/babel-loader/
						// directory for faster rebuilds.
						cacheDirectory: true,
					},
				},
			},
		],
	},
	// Add plugins.
	plugins: [
		// Minify the code.
		new webpack.optimize.UglifyJsPlugin( {
			compress: {
				warnings: false,
				// Disabled because of an issue with Uglify breaking seemingly valid code:
				// https://github.com/facebookincubator/create-react-app/issues/2376
				// Pending further investigation:
				// https://github.com/mishoo/UglifyJS2/issues/2011
				comparisons: false,
			},
			mangle: {
				safari10: true,
				except: ['__', '_n', '_x', '_nx' ],
			},
			output: {
				comments: false,
				// Turned on because emoji and regex is not minified properly using default
				// https://github.com/facebookincubator/create-react-app/issues/2488
				ascii_only: true,
			},
			sourceMap: shouldUseSourceMap,
		} ),
	],
	stats: 'minimal',
	// stats: 'errors-only',
	// Add externals.
	externals: externals,
};
