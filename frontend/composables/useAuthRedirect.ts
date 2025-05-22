import { useOAuth } from "~/composables/sso/useOAuth";

export const useAuthRedirect = () => {
    const { redirectToAuthorization } = useOAuth(); 
    const accessToken = useCookie("access_token").value;
  
    const checkAuthAndNavigate = (route: string) => {
      if (!accessToken) {
        redirectToAuthorization();
        return false;
      }
      return navigateTo(route);
    };
  
    return {
      checkAuthAndNavigate
    };
  };