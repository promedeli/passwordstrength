const path = require('path');

module.exports = {
    mode: 'production',
    entry: './public/js/loader.js',
    output: {
        path: __dirname + '/views/js',
        filename: 'passwordstrength.js'
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            }
        ]
    }
};
