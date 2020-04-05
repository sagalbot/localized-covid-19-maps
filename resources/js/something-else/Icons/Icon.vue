<template>
  <svg-vue :icon="name" class="fill-current icon" :class="`w-${size}`" />
</template>

<script>
import { ICONS } from './icons';

const sizes = [
  '.w-0',
  '.w-1',
  '.w-2',
  '.w-3',
  '.w-4',
  '.w-5',
  '.w-6',
  '.w-8',
  '.w-10',
  '.w-12',
  '.w-16',
  '.w-20',
  '.w-24',
  '.w-32',
  '.w-40',
  '.w-48',
  '.w-56',
  '.w-64'
];

export default {
  name: 'Icon',
  props: {
    name: {
      type: String,
      required: true,
      validate: icon => ICONS.includes(icon)
    },
    size: {
      type: [String, Number],
      default: 6,
      validate: value => sizes.some(size => size.includes(value))
    }
  }
};
</script>
