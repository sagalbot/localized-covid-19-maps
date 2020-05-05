import { chartColors } from './colors';
import { merge } from 'lodash-es';
import abbr from 'sugar/number/abbr';

const chartConfig = (options = {}) =>
  merge(
    {
      chart: {
        redrawOnParentResize: false,
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
        strokeWidth: 0,
        hover: {
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
      }
    },
    options
  );

export { chartConfig };
