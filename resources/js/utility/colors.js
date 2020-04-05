import resolveConfig from 'tailwindcss/resolveConfig';
import { shuffle } from 'lodash-es';

const chartColors = () => {
  const colors = resolveConfig().theme.colors;
  const desiredColors = [
    'blue',
    'green',
    'indigo',
    'red',
    'teal',
    'yellow',
    'orange'
  ];
  const desiredIntensities = [500];

  const resolvedColors = Object.keys(colors)
    .filter(color => desiredColors.includes(color))
    .map(color => {
      return desiredIntensities.map(weight => colors[color][weight]);
    })
    .flat();

  return shuffle(resolvedColors);
};

export { chartColors };
