export const useLocale = () => {
    const locale = useState('locale', () => 'bn'); 
    const translations = useState('translations', () => ({}));

    const fetchTranslations = async (selectedLocale: string) => {
      try {
          const { data } = await useFetch(`/translations/${selectedLocale}`, {
              baseURL: useRuntimeConfig().public.apiURL,
              method: 'GET',
            });
            translations.value = data || {};
      } catch (error) {
        translations.value = {};
      }
    };
  
    const t = (key: string) => {
      return translations.value[key] || key;
    };
  
    const changeLocale = async (newLocale: string) => {
      locale.value = newLocale;
      await fetchTranslations(newLocale);
    };
  
    return { locale, translations, t, changeLocale };
  };
  