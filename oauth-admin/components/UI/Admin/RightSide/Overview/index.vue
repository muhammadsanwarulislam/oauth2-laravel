<template>
  <div class="chart-container">
    <h5 class="text-lg font-medium text-gray-800 mb-6">{{ t('welcome') }}</h5>

    <div class="chart-wrapper bg-white rounded-lg shadow-sm p-4">
      <div class="chart-header flex flex-wrap justify-between items-center mb-4">
        <h6 class="text-base font-semibold text-gray-700">Overview Graph</h6>
        
        <div class="chart-controls flex items-center space-x-4">
          <div class="chart-type-selector">
            <label for="chartType" class="sr-only">Chart Type</label>
            <select 
              v-model="chartType" 
              id="chartType" 
              class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="bar">Bar Chart</option>
              <option value="line">Line Chart</option>
              <option value="pie">Pie Chart</option>
              <option value="area">Area Chart</option>
            </select>
          </div>
          
          <button 
            @click="randomizeData"
            class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-md transition-colors"
          >
            Randomize Data
          </button>
        </div>
      </div>

      <div class="chart-content">
        <svg 
          :width="chartWidth" 
          :height="chartHeight" 
          :viewBox="`0 0 ${chartWidth} ${chartHeight}`"
          class="chart-svg"
        >
          <!-- X-axis for bar/line/area charts -->
          <line 
            v-if="!isPieChart"
            x1="0" 
            :y1="chartHeight - padding" 
            :x2="chartWidth - padding" 
            :y2="chartHeight - padding" 
            stroke="#e5e7eb" 
            stroke-width="1"
          />
          
          <!-- Y-axis for bar/line/area charts -->
          <line 
            v-if="!isPieChart"
            x1="padding" 
            y1="0" 
            :x2="padding" 
            :y2="chartHeight - padding" 
            stroke="#e5e7eb" 
            stroke-width="1"
          />
          
          <!-- Y-axis labels -->
          <template v-if="!isPieChart">
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
          </template>

          <!-- Bar Chart -->
          <g v-if="chartType === 'bar'">
            <rect 
              v-for="(value, index) in normalizedData" 
              :key="`bar-${index}`"
              :x="padding + index * (barWidth + barGap)" 
              :y="chartHeight - padding - value" 
              :width="barWidth" 
              :height="value"
              :fill="colors[index % colors.length]"
              rx="2" 
              class="bar hover:opacity-90 transition-opacity"
              @mouseenter="showTooltip(index)"
              @mouseleave="hideTooltip"
            />
          </g>
          
          <!-- Line Chart -->
          <g v-if="chartType === 'line'">
            <polyline 
              :points="linePoints" 
              fill="none" 
              stroke="#3b82f6" 
              stroke-width="3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <circle 
              v-for="(value, index) in normalizedData" 
              :key="`point-${index}`"
              :cx="padding + index * (barWidth + barGap) + barWidth / 2" 
              :cy="chartHeight - padding - value" 
              :r="4" 
              fill="#3b82f6"
              class="cursor-pointer hover:r-6 transition-all"
              @mouseenter="showTooltip(index)"
              @mouseleave="hideTooltip"
            />
          </g>
          
          <!-- Area Chart -->
          <g v-if="chartType === 'area'">
            <polygon 
              :points="areaPoints" 
              fill="url(#areaGradient)" 
              fill-opacity="0.7"
            />
            <polyline 
              :points="linePoints" 
              fill="none" 
              stroke="#3b82f6" 
              stroke-width="3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <circle 
              v-for="(value, index) in normalizedData" 
              :key="`point-${index}`"
              :cx="padding + index * (barWidth + barGap) + barWidth / 2" 
              :cy="chartHeight - padding - value" 
              :r="4" 
              fill="#3b82f6"
              class="cursor-pointer hover:r-6 transition-all"
              @mouseenter="showTooltip(index)"
              @mouseleave="hideTooltip"
            />
          </g>
          
          <!-- Pie Chart -->
          <g v-if="chartType === 'pie'" :transform="`translate(${chartWidth/2}, ${chartHeight/2})`">
            <path 
              v-for="(value, index) in data" 
              :key="`pie-${index}`" 
              :d="pieSlicePaths[index]"
              :fill="colors[index % colors.length]"
              class="pie-slice hover:opacity-90 transition-opacity"
              @mouseenter="showTooltip(index)"
              @mouseleave="hideTooltip"
            />
          </g>
          
          <!-- X-axis labels -->
          <text 
            v-if="!isPieChart"
            v-for="(label, index) in xAxisLabels" 
            :key="`x-label-${index}`"
            :x="padding + index * (barWidth + barGap) + barWidth / 2" 
            :y="chartHeight - 5" 
            class="text-xs fill-gray-500"
            text-anchor="middle"
          >
            {{ label }}
          </text>
          
          <!-- Gradient for area chart -->
          <defs>
            <linearGradient id="areaGradient" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stop-color="#3b82f6" />
              <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.2" />
            </linearGradient>
          </defs>
        </svg>
      </div>
      
      <!-- Tooltip -->
      <div 
        v-if="tooltip.visible"
        class="tooltip absolute bg-white border border-gray-200 rounded-md shadow-md p-2 text-sm"
        :style="{
          left: `${tooltip.x}px`,
          top: `${tooltip.y}px`
        }"
      >
        <div class="font-medium">{{ tooltip.label }}</div>
        <div>Value: {{ tooltip.value }}</div>
        <div v-if="isPieChart">Percentage: {{ tooltip.percentage }}%</div>
      </div>
    </div>

    <div class="dashboard-grid">
    <CommonChartDisplay 
      title="Revenue" 
      description="Quarterly revenue breakdown"
      :initial-data="[12000, 18000, 15000, 22000]"
      chart-type="bar"
    />
    
    <CommonChartDisplay 
      title="User Engagement" 
      description="Weekly engagement metrics"
      :initial-data="[75, 82, 68, 90, 88, 76, 95]"
      chart-type="line"
    />
  </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useLocale } from '~/composables/useLocale';

const { t } = useLocale();

// Configuration
const padding = 30;
const barWidth = 30;
const barGap = 10;
const chartHeight = 300;
const chartWidth = computed(() => {
  return isPieChart.value ? 300 : data.value.length * (barWidth + barGap) + padding * 2;
});

const chartType = ref('bar');
const isPieChart = computed(() => chartType.value === 'pie');
const colors = ['#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#ec4899', '#6b7280'];

// Data
const data = ref([40, 70, 90, 60, 100, 50, 80]);
const xAxisLabels = computed(() => 
  data.value.map((_, i) => `Item ${i + 1}`)
);

// Tooltip
const tooltip = ref({
  visible: false,
  x: 0,
  y: 0,
  label: '',
  value: 0,
  percentage: 0
});

// Y-axis calculations
const maxDataValue = computed(() => Math.max(...data.value, 1));
const yAxisTicks = computed(() => {
  const ticks = [];
  const steps = 5;
  const stepValue = Math.ceil(maxDataValue.value / steps);
  
  for (let i = 0; i <= steps; i++) {
    ticks.push(i * stepValue);
  }
  
  return ticks;
});
const yAxisInterval = computed(() => {
  return (chartHeight - padding * 2) / (yAxisTicks.value.length - 1);
});

// Normalized data for charts
const normalizedData = computed(() => {
  const chartAreaHeight = chartHeight - padding * 2;
  return data.value.map(value => (value / maxDataValue.value) * chartAreaHeight);
});

// Line points for line and area charts
const linePoints = computed(() => {
  return normalizedData.value
    .map((value, index) => {
      const x = padding + index * (barWidth + barGap) + barWidth / 2;
      const y = chartHeight - padding - value;
      return `${x},${y}`;
    })
    .join(' ');
});

// Area points
const areaPoints = computed(() => {
  const points = normalizedData.value.map((value, index) => {
    const x = padding + index * (barWidth + barGap) + barWidth / 2;
    const y = chartHeight - padding - value;
    return `${x},${y}`;
  });
  const firstX = padding + barWidth / 2;
  const lastX = padding + (data.value.length - 1) * (barWidth + barGap) + barWidth / 2;
  return `${firstX},${chartHeight - padding} ${points.join(' ')} ${lastX},${chartHeight - padding}`;
});

// Pie chart calculations
const totalData = computed(() => data.value.reduce((sum, value) => sum + value, 0));
const pieSlicePaths = computed(() => {
  let startAngle = 0;
  const radius = Math.min(chartWidth.value, chartHeight) / 2 - 20;
  
  return data.value.map((value) => {
    const angle = (value / totalData.value) * 2 * Math.PI;
    const endAngle = startAngle + angle;
    const largeArc = angle > Math.PI ? 1 : 0;
    
    const startX = radius * Math.cos(startAngle);
    const startY = radius * Math.sin(startAngle);
    const endX = radius * Math.cos(endAngle);
    const endY = radius * Math.sin(endAngle);
    
    const path = `M 0 0 L ${startX} ${startY} A ${radius} ${radius} 0 ${largeArc} 1 ${endX} ${endY} Z`;
    startAngle = endAngle;
    return path;
  });
});

// Methods
const randomizeData = () => {
  data.value = data.value.map(() => Math.floor(Math.random() * 100) + 20);
};

const showTooltip = (index) => {
  const value = data.value[index];
  tooltip.value = {
    visible: true,
    x: event.clientX + 10,
    y: event.clientY + 10,
    label: xAxisLabels.value[index],
    value: value,
    percentage: isPieChart.value ? ((value / totalData.value) * 100).toFixed(1) : 0
  };
};

const hideTooltip = () => {
  tooltip.value.visible = false;
};

// Initialize with some random data
onMounted(randomizeData);
</script>

<style scoped>
.chart-container {
  @apply w-full h-full;
}

.chart-wrapper {
  @apply relative;
}

.chart-svg {
  @apply w-full;
}

.bar {
  transition: opacity 0.2s ease;
}

.pie-slice {
  transition: opacity 0.2s ease;
}

.tooltip {
  z-index: 100;
  pointer-events: none;
  transition: opacity 0.2s ease;
}
</style>