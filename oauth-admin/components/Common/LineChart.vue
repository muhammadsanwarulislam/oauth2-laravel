<template>
  <svg 
    :width="chartWidth" 
    :height="chartHeight" 
    :viewBox="`0 0 ${chartWidth} ${chartHeight}`"
    class="chart-svg"
  >
    <!-- X-axis -->
    <line 
      x1="0" 
      :y1="chartHeight - padding" 
      :x2="chartWidth - padding" 
      :y2="chartHeight - padding" 
      stroke="#e5e7eb" 
      stroke-width="1"
    />
    
    <!-- Y-axis -->
    <line 
      x1="padding" 
      y1="0" 
      :x2="padding" 
      :y2="chartHeight - padding" 
      stroke="#e5e7eb" 
      stroke-width="1"
    />
    
    <!-- Y-axis labels -->
    <text 
      v-for="(tick, index) in yAxisTicks" 
      :key="`y-tick-${index}`"
      x="10" 
      :y="chartHeight - padding - (index * yAxisInterval)" 
      class="text-xs fill-gray-500"
      text-anchor="end"
      dominant-baseline="middle"
    >
      {{ tick }}
    </text>

    <!-- Line -->
    <polyline 
      :points="linePoints" 
      fill="none" 
      stroke="#3b82f6" 
      stroke-width="3"
      stroke-linecap="round"
      stroke-linejoin="round"
    />
    
    <!-- Points -->
    <circle 
      v-for="(value, index) in normalizedData" 
      :key="`point-${index}`"
      :cx="padding + index * (barWidth + barGap) + barWidth / 2" 
      :cy="chartHeight - padding - value" 
      :r="4" 
      fill="#3b82f6"
      class="cursor-pointer hover:r-6 transition-all"
      @mouseenter="showTooltip(index, $event)"
      @mouseleave="hideTooltip"
    />

    <!-- X-axis labels -->
    <text 
      v-for="(label, index) in xAxisLabels" 
      :key="`x-label-${index}`"
      :x="padding + index * (barWidth + barGap) + barWidth / 2" 
      :y="chartHeight - 5" 
      class="text-xs fill-gray-500"
      text-anchor="middle"
    >
      {{ label }}
    </text>
  </svg>
</template>

<script setup>
const props = defineProps({
  chartData: {
    type: Array,
    required: true
  }
})

const {
  padding,
  barWidth,
  barGap,
  chartHeight,
  data,
  tooltip,
  xAxisLabels,
  chartWidth,
  normalizedData,
  yAxisTicks,
  yAxisInterval,
  linePoints,
  showTooltip,
  hideTooltip
} = useChart(props.chartData, 'line')
</script>

<style scoped>
.chart-svg {
  @apply w-full;
}
</style>