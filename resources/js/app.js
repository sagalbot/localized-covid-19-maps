import { InertiaApp } from '@inertiajs/inertia-vue';
import VueGoogleCharts from 'vue-google-charts';
import SvgVue from 'svg-vue';
import Vue from 'vue';

Vue.config.productionTip = false;

Vue.use(InertiaApp);
Vue.use(VueGoogleCharts);
Vue.use(SvgVue);

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
