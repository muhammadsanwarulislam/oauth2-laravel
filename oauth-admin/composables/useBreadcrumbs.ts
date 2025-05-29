import { useLocale } from '~/composables/useLocale';
export function useBreadcrumbs(resetForm: any, showForm: any, labels: any) {
  const { t } = useLocale();

  const breadcrumbs = computed(() => [
    {
      label: t(labels.home),
      icon: `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6 13h12v-2H6M3 6v2h18V6M10 18h4v-2h-4z"/></svg>
      `,
      action: resetForm,
    },
    {
      label: t(labels.creation),
      icon: `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6z"/></svg>
      `,
      action: showForm,
    },
  ]);

  return {
    breadcrumbs,
  };
}
