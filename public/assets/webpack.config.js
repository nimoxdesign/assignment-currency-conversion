const path = require('path');

const cssFilenamePrefix = 'styles';

module.exports = {
	target: 'web',
	mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
	entry: [
		path.resolve(__dirname, './src/js/index.js'),
		path.resolve(__dirname, './src/scss/' + cssFilenamePrefix + '.scss'), 
		path.resolve(__dirname, './src/scss/' + cssFilenamePrefix + '-responsive.scss')
	],
	output: {
		path: path.resolve(__dirname, './dist'),
		filename: 'js/bundle.js',
	},
	module: {
		rules: [
			// {
			// 	test: /\.js$/,
			// 	use: [
			// 		{
			// 			loader: 'file-loader',
			// 			options: {
			// 				name: 'js/[name].js',
			// 			}
			// 		}
			// 	]
			// },
			{
				test: /\.m?js$/,
				exclude: /node_modules/,
				use: {
					loader: "babel-loader",
					options: {
						presets: ['@babel/preset-env']
					}
				}
			},
			{
				test: /\.scss$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: 'css/[name].css',
						}
					},
					{
						loader: 'extract-loader'
					},
					{
						loader: 'css-loader?-url'
					},
					{
						loader: 'postcss-loader'
					},
					{
						loader: 'sass-loader'
					}
				]
			},
			{
				test: /\.(png|svg|jpg|jpeg|gif)$/i,
				type: 'javascript/auto',
				use: [
					{
						loader: 'file-loader',
						options: {
							name: 'img/[name].[ext]',
							limit: 8192,
						}
					},
				],
				// use: [
				// 	{
				// 		loader: 'asset/resource',
				// 		options: {
				// 			name: 'img/[name]',
				// 		}
				// 	},
				// ]
			},
			{
				test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
				type: 'javascript/auto',
				use: [
					{
						loader: 'file-loader',
						options: {
							name: 'fonts/[name].[ext]',
							limit: 8192,
						}
					},
				],
			},
		]
	}
};
