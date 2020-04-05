import { chartColors } from './colors';
import abbr from 'sugar/number/abbr';

const chartConfig = (options = {}) => ({
  chart: {
    fontFamily:
      'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
  },
  colors: chartColors(),
  stroke: {
    show: true,
    lineCap: 'butt',
    width: 2,
    dashArray: 0
  },
  markers: {
    size: 2,
    // colors: undefined,
    // strokeColors: 'transparent',
    strokeWidth: 0,
    // strokeOpacity: 0.9,
    // strokeDashArray: 0,
    // fillOpacity: 1,
    // discrete: [],
    // shape: "circle",
    // radius: 2,
    // offsetX: 0,
    // offsetY: 0,
    // onClick: undefined,
    // onDblClick: undefined,
    // showNullDataPoints: true,
    hover: {
      // size: undefined,
      sizeOffset: 0
    }
  },
  yaxis: {
    labels: {
      formatter: value => {
        const precision = parseFloat(value) >= 100000 ? 1 : 2;
        return abbr(value, precision);
      }
    }
  },

  ...options
});

export { chartConfig };
