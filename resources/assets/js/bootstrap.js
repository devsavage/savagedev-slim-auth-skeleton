window._ = require('lodash');
window.Popper = require('@popperjs/core').default;

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}
