// composables/useChart.js
import { ref, computed } from 'vue'

export default function useChart(initialData, initialType = 'bar') {
  // Configuration
  const padding = 30
  const barWidth = 30
  const barGap = 10
  const chartHeight = 300
  const colors = ['#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#ec4899', '#6b7280']

  // Reactive state
  const chartType = ref(initialType)
  const data = ref(initialData)
  const tooltip = ref({
    visible: false,
    x: 0,
    y: 0,
    label: '',
    value: 0,
    percentage: 0
  })

  // Computed properties
  const isPieChart = computed(() => chartType.value === 'pie')
  const xAxisLabels = computed(() => data.value.map((_, i) => `Item ${i + 1}`))
  const maxDataValue = computed(() => Math.max(...data.value, 1))
  const totalData = computed(() => data.value.reduce((sum, value) => sum + value, 0))
  
  const chartWidth = computed(() => {
    return isPieChart.value ? 300 : data.value.length * (barWidth + barGap) + padding * 2
  })

  const normalizedData = computed(() => {
    const chartAreaHeight = chartHeight - padding * 2
    return data.value.map(value => (value / maxDataValue.value) * chartAreaHeight)
  })

  const yAxisTicks = computed(() => {
    const ticks = []
    const steps = 5
    const stepValue = Math.ceil(maxDataValue.value / steps)
    
    for (let i = 0; i <= steps; i++) {
      ticks.push(i * stepValue)
    }
    
    return ticks
  })

  const yAxisInterval = computed(() => {
    return (chartHeight - padding * 2) / (yAxisTicks.value.length - 1)
  })

  const linePoints = computed(() => {
    return normalizedData.value
      .map((value, index) => {
        const x = padding + index * (barWidth + barGap) + barWidth / 2
        const y = chartHeight - padding - value
        return `${x},${y}`
      })
      .join(' ')
  })

  const areaPoints = computed(() => {
    const points = normalizedData.value.map((value, index) => {
      const x = padding + index * (barWidth + barGap) + barWidth / 2
      const y = chartHeight - padding - value
      return `${x},${y}`
    })
    const firstX = padding + barWidth / 2
    const lastX = padding + (data.value.length - 1) * (barWidth + barGap) + barWidth / 2
    return `${firstX},${chartHeight - padding} ${points.join(' ')} ${lastX},${chartHeight - padding}`
  })

  const pieSlicePaths = computed(() => {
    let startAngle = 0
    const radius = Math.min(chartWidth.value, chartHeight) / 2 - 20
    
    return data.value.map((value) => {
      const angle = (value / totalData.value) * 2 * Math.PI
      const endAngle = startAngle + angle
      const largeArc = angle > Math.PI ? 1 : 0
      
      const startX = radius * Math.cos(startAngle)
      const startY = radius * Math.sin(startAngle)
      const endX = radius * Math.cos(endAngle)
      const endY = radius * Math.sin(endAngle)
      
      const path = `M 0 0 L ${startX} ${startY} A ${radius} ${radius} 0 ${largeArc} 1 ${endX} ${endY} Z`
      startAngle = endAngle
      return path
    })
  })

  // Methods
  const randomizeData = () => {
    data.value = data.value.map(() => Math.floor(Math.random() * 100) + 20)
  }

  const showTooltip = (index, event) => {
    const value = data.value[index]
    tooltip.value = {
      visible: true,
      x: event.clientX + 10,
      y: event.clientY + 10,
      label: xAxisLabels.value[index],
      value: value,
      percentage: isPieChart.value ? ((value / totalData.value) * 100).toFixed(1) : 0
    }
  }

  const hideTooltip = () => {
    tooltip.value.visible = false
  }

  return {
    // Configuration
    padding,
    barWidth,
    barGap,
    chartHeight,
    colors,
    
    // State
    chartType,
    data,
    tooltip,
    
    // Computed
    isPieChart,
    xAxisLabels,
    chartWidth,
    normalizedData,
    yAxisTicks,
    yAxisInterval,
    linePoints,
    areaPoints,
    pieSlicePaths,
    
    // Methods
    randomizeData,
    showTooltip,
    hideTooltip
  }
}