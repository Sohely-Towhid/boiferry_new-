try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
    window.Swal = require('sweetalert2');
    // window.flatpickr = require('flatpickr');
    const flatpickr = require("flatpickr");
    require('zeynepjs')($);
} catch (e) {}

// require('../web/js/jquery.min.js');
// require('../web/js/jquery-migrate.min.js');
// require('../web/js/popper.min.js');
// require('../web/js/bootstrap.min.js');
require('../web/js/bootstrap-select.min.js');
require('../web/js/slick.min.js');
// require('../web/js/jquery.zeynep.js');
require('../web/js/jquery.mCustomScrollbar.concat.min.js');
require('../web/js/bootstrap-autocomplete.min.js');
require('../web/js/jquery.lazy.min.js');
// require('../web/js/jquery.zeynep.js');

require('../web/js/hs.core.js');
require('../web/js/hs.slick-carousel.js');
require('../web/js/hs.unfold.js');
require('../web/js/hs.malihu-scrollbar.js');
require('../web/js/hs.header.js');
require('../web/js/hs.selectpicker.js');
require('../web/js/hs.show-animation.js');

require('../web/js/shop.js');
