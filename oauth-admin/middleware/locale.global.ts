export default defineNuxtRouteMiddleware(async () => {
    const { changeLocale } = useLocale();
    const userLocale = useCookie('locale').value || 'en'; 
    await changeLocale(userLocale);
  });
  