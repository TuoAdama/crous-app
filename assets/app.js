import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
import "@fortawesome/fontawesome-free/js/all.min";
import "@popperjs/core/dist/cjs/popper"
registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));