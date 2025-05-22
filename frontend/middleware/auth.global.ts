import { useUser } from "~/composables/sso/useUser";
import { useOAuth } from "~/composables/sso/useOAuth";

export default defineNuxtRouteMiddleware(async (to, from) => {
  const { userData, setUserData } = useUser();
  const { redirectToAuthorization, fetchUserData, processAuthorizationCodeToGenerateAccessToken } = useOAuth();
  const accessToken = useCookie("access_token").value;
  const authorizationCode: any = to.query.code;

  const ensureUserData = async () => {
    try {
      const fetchedUserData = await fetchUserData();
      setUserData(fetchedUserData);
    } catch (error) {
      return redirectToAuthorization();
    }
  };

  if (authorizationCode) {
    try {
      await processAuthorizationCodeToGenerateAccessToken(authorizationCode);
      await ensureUserData();

      // Check if there was an original path saved
      const redirectCookie = useCookie("redirect_after_login");
      const intendedPath = redirectCookie.value;

      if (intendedPath) {
        // Clear the cookie after use
        redirectCookie.value = null;
        return navigateTo(intendedPath, { replace: true });
      }

      return navigateTo("/citizen", { replace: true });
      
    } catch (error) {
      return redirectToAuthorization();
    }
  }

  if (accessToken && !userData.value) {
    await ensureUserData();
  }

  if (!accessToken) {
    if (to.path.startsWith("/citizen/service-info/") || 
        to.path.startsWith("/citizen/service/") || 
        to.path.startsWith("/certificate") ||
        to.path.startsWith("/about") || 
        to.path.startsWith("/contact") || 
        to.path.startsWith("/userguide") || 
        to.path.startsWith("/faq") 
        ) {
      // Allow public access to service-info and service pages
      return;
    } else if (to.path !== "/") {
      // Save the intended path before redirecting
      const redirectCookie = useCookie("redirect_after_login");
      redirectCookie.value = to.fullPath;
      return navigateTo("/");
    }
  }

  if (accessToken && to.path === "/") {
    return navigateTo("/citizen", { replace: true });
  }

  if(to.path.startsWith("/certificate?")) {
    to.meta.layout = '';
  }
  else if (accessToken) {
    to.meta.layout = "citizen";
  } else {
    to.meta.layout = "default";
  }
});
