import { useLocale } from '~/composables/useLocale';
export function useBreadcrumbs(resetForm: any, showForm: any, labels: any) {
  const { t } = useLocale();

  const breadcrumbs = computed(() => [
    {
      label: t(labels.home),
      icon: `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" d="M3 12l1.5-1.5L12 3l7.5 7.5L21 12l-9 9-9-9z"/>
        </svg>
      `,
      action: resetForm,
    },
    {
      label: t(labels.creation),
      icon: `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" d="M12 2l4 4H8l4-4zM2 12l4-4v8l-4-4zm20 0l-4-4v8l4-4zM12 22l-4-4h8l-4 4z"/>
        </svg>
      `,
      action: showForm,
    },
  ]);

  return {
    breadcrumbs,
  };
}
