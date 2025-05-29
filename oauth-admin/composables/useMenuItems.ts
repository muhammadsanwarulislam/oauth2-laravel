import { useLocale } from '~/composables/useLocale';
export interface MenuItem {
  label: string;
  component: string;
  icon: string;
}

export function useMenuItems() {
  const { t } = useLocale();

  const menus = computed<MenuItem[]>(() => [
    { label: t('overview'), component: 'Overview', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { label: t('user'), component: 'UserCreation', icon: 'M9 12l2 2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { label: t('client'), component: 'ClientCreation', icon: 'M11 14H9a9 9 0 0 1 9-9v2c-3.87 0-7 3.13-7 7m7-3V9c-2.76 0-5 2.24-5 5h2c0-1.66 1.34-3 3-3M7 4c0-1.11-.89-2-2-2s-2 .89-2 2s.89 2 2 2s2-.89 2-2m4.45.5h-2A2.99 2.99 0 0 1 6.5 7h-3C2.67 7 2 7.67 2 8.5V11h6V8.74a4.97 4.97 0 0 0 3.45-4.24M19 17c1.11 0 2-.89 2-2s-.89-2-2-2s-2 .89-2 2s.89 2 2 2m1.5 1h-3a2.99 2.99 0 0 1-2.95-2.5h-2A4.97 4.97 0 0 0 16 19.74V22h6v-2.5c0-.83-.67-1.5-1.5-1.5' }
  ]);

  return {
    menus,
  };
}
