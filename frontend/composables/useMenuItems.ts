import { useLocale } from '~/composables/localization/useLocale';

export interface MenuItem {
  label: string;
  component: string;
  route: string;
}

export function useMenuItems() {
  const { t } = useLocale();

  const menus = computed<MenuItem[]>(() => [
    { label: t('FAQs'), component: '', route: '/faq' },
    { label: t('about'), component: '', route: '/about' },
    { label: t('userguide'), component: '', route: '/userguide' },
    { label: t('contact'), component: '', route: '/contact' },
  ]);

  return {
    menus,
  };
}
