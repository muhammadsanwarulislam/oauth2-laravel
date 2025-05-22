import { useLocale } from '~/composables/useLocale';
export interface MenuItem {
  label: string;
  component: string;
  icon: string;
}

export function useMenuItems() {
  const { t } = useLocale();

  const menus = computed<MenuItem[]>(() => [
    { label: t('overview'), component: 'Overview', icon: 'M3 10h11M9 21V3m11 18l-6-6m0 0l6-6m-6 6h11' },
    { label: t('user'), component: 'UserCreation', icon: 'M16 12a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0v8m-8-8v8m8-4h-8' },
    { label: t('client'), component: 'ClientCreation', icon: 'M9 12l2 2m0 0l2-2m-2 2V6m0 8v6m7-14a7 7 0 01-14 0m14 0a7 7 0 00-14 0' }
  ]);

  return {
    menus,
  };
}
