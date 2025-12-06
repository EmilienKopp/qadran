<script lang="ts">
  interface Props {
    size?: number;
    backgroundColor?: string;
    dotColor?: string;
    duration?: number;
  }

  let {
    size = 48,
    backgroundColor = 'bg-blue-500',
    dotColor = 'bg-white',
    duration = 2,
  }: Props = $props();

  const dotSize = size * 0.40; // 40% of parent size
  const startPos = -(dotSize / 2);
  const centerPos = (size / 2) - (dotSize / 2);
  const endPos = size - (dotSize / 2);
</script>

<div
  class="relative overflow-hidden rounded-full {backgroundColor}"
  style="width: {size}px; height: {size}px;"
>
  <span
    class="rotating-dot absolute top-1/2 rounded-full {dotColor}"
    style="width: {dotSize}px; height: {dotSize}px; --start: {startPos}px; --center: {centerPos}px; --end: {endPos}px; animation-duration: {duration}s;"
  >
  </span>
</div>

<style>
  .rotating-dot {
    left: 0;
    animation-name: rotate-effect;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
  }

  @keyframes rotate-effect {
    0% {
      transform: translateX(var(--start)) translateY(-50%) scale(0.5) scaleX(0.5);
      opacity: 1;
    }
    25% {
      transform: translateX(var(--center)) translateY(-50%) scale(1) scaleX(1);
      opacity: 1;
    }
    50% {
      transform: translateX(var(--end)) translateY(-50%) scale(0.5) scaleX(0.5);
      opacity: 1;
    }
    50.01% {
      opacity: 0.3;
    }
    75% {
      transform: translateX(var(--center)) translateY(-50%) scale(0.1) scaleX(1);
      opacity: 0.2;
    }
    100% {
      transform: translateX(var(--start)) translateY(-50%) scale(0.5) scaleX(0.5);
      opacity: 0.3;
    }
  }
</style>
