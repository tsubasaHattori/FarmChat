
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('vue-moment'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('component-message-form', require('./components/_component_message_form.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

$('.nav-toggler').click(function() {
    $('.nav-toggle-container').toggleClass('hidden');
})

$(function () {
    // ナビの範囲外のどこかをクリックしたときに発動
    $('#app').on('click', function () {
        if(!$(event.target).closest('.nav-toggle-container').length && !$(event.target).closest('.nav-toggler').length){
            if ($('.nav-toggle-container').is(':visible')) {
                // ナビが表示されていたらcloseを実行
                $('.nav-toggler').trigger('click');
            } else {
                // ナビが非表示の場合は起動しない
                event.stopPropagation();
            }
        }
    });
});
