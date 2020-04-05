import { InertiaApp } from '@inertiajs/inertia-vue';
import VueGoogleCharts from 'vue-google-charts';
import SvgVue from 'svg-vue';
import Vue from 'vue';
import registerComponents from './components/globalComponents';
import ZiggyMixin from './mixins/ZiggyMixin';

Vue.config.productionTip = false;

Vue.use(InertiaApp);
Vue.use(VueGoogleCharts);
Vue.use(SvgVue);

ZiggyMixin();
registerComponents(Vue);

const app = document.getElementById('app');

new Vue({
  render: h =>
    h(InertiaApp, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: name =>
          import(`./Pages/${name}`).then(module => module.default)
      }
    })
}).$mount(app);
