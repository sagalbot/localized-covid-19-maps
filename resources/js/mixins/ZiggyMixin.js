import Vue from 'vue';
import route from 'ziggy';

export default () => {
  Vue.mixin({
    methods: {
      route: (name, params, absolute) =>
        route(name, params, absolute, window.Ziggy),
      routeActive(name, params) {
        const routeObj = route(name, params, false, window.Ziggy);
        return window.location.pathname.includes(routeObj.toString());
      }
    }
  });
};
