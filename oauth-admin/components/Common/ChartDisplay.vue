<template>
  <div class="chart-container">
    <h5 class="text-lg font-medium text-gray-800 mb-6">{{ title }}</h5>

    <div class="chart-wrapper bg-white rounded-lg shadow-sm p-4">
      <div class="chart-header flex flex-wrap justify-between items-center mb-4">
        <h6 class="text-base font-semibold text-gray-700">{{ description }}</h6>
        
        <div class="chart-controls flex items-center space-x-4" v-if="showControls">
          <button 
            @click="randomizeData"
            class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-md transition-colors"
          >
            Randomize Data
          </button>
        </div>
      </div>

      <div class="chart-content">
        <component 
          :is="chartComponent" 
          :chart-data="data"
        />
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
  </div>
</template>

<script setup>
import { computed } from 'vue'
import BarChart from '@/components/Common/BarChart.vue'
import LineChart from '@/components/Common/LineChart.vue'
import useChart from '@/composables/useChart'

const props = defineProps({
  title: {
    type: String,
    default: 'Chart'
  },
  description: {
    type: String,
    default: 'Data Visualization'
  },
  initialData: {
    type: Array,
    required: true
  },
  chartType: {
    type: String,
    default: 'bar',
    validator: (value) => ['bar', 'line', 'pie', 'area'].includes(value)
  },
  showControls: {
    type: Boolean,
    default: true
  }
})

const chartComponents = {
  bar: BarChart,
  line: LineChart,
}

const chartComponent = computed(() => chartComponents[props.chartType])

const {
  data,
  tooltip,
  isPieChart,
  randomizeData
} = useChart(props.initialData, props.chartType)
</script>

<style scoped>
.tooltip {
  z-index: 100;
  pointer-events: none;
  transition: opacity 0.2s ease;
}
</style>