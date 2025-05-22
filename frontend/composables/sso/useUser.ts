export const useUser = () => {
  const userData = useState('userData', () => JSON.parse(localStorage.getItem('userData') || 'null'));

  const setUserData = (data: any) => {
    userData.value = data;
    localStorage.setItem('userData', JSON.stringify(data));
  };

  const clearUserData = () => {    
    userData.value = null;
    const accessToken = useCookie("access_token");
    accessToken.value = null;
    localStorage.removeItem("access_token");
    window.location.href = "/";
  };

  return {
    userData,
    setUserData,
    clearUserData,
  };
};

