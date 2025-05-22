<template>
  <div class="flex flex-col min-h-screen">
    <!-- Header -->
     <div class="bg-gray-500">
       <UIGuestHeader name="default-header" />
     </div>
      
    <!-- Main Content Area -->
    <div class="container mx-auto flex-grow">
      <slot />
    </div>

    <!-- Footer -->
    <div class="bg-white">
      <UIGuestFooter name="default-footer" />
    </div>
  </div>
</template>
  
  