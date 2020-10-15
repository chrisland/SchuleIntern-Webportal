const path = require('path');
const baseConfig = require('@zwiebel-intern/webpack-base-config');

module.exports = {
    ...baseConfig,

    output: {
        path: path.resolve(__dirname, 'build'),
    },

    entry: {
        main: [
            './src/main.js',
            './src/main.scss',
        ],
    },
};
