interface UserResponse {
  data: {
    user: {
      id: string;
    };
  };
}

interface AuthorizeResponse {
  code: number;
  data: {
    redirect: string;
    code: string;
    message?: string;
  };
}

import { API_ENDPOINTS } from "~/config/api";

export const useAuthorization = () => {
  const route = useRoute();

  const clientId = ref<string>("");
  const redirectUri = ref<string>("");
  const userId = ref<string>("");
  const isLoading = ref<boolean>(false);
  const isSuccess = ref<boolean>(false);
  const error = ref<string | null>(null); 

  const FRONTEND_URL = useRuntimeConfig().public.frontendURL;

  const initialize = (): void => {
    clientId.value = (route.query.client_id as string) || "";
    redirectUri.value = (route.query.redirect as string) || "";

    if (!clientId.value || !redirectUri.value) {
      error.value = "Missing required query parameters: client_id or redirect";
      console.error(error.value);
    }
  };

  const checkUserAuthentication = async (): Promise<boolean> => {
    try {
      const response = await $http(`${API_ENDPOINTS.CURRENT_USER}`);
      userId.value = response.data.user.id;
      error.value = null; 
      return true;
    } catch (err) {
      error.value = "You must be logged in to approve this request.";
      return false;
    }
  };


  const approveAuthorization = async (): Promise<void> => {
    isLoading.value = true;
    isSuccess.value = false;
    error.value = null; 

    try {
      const response = await $http<AuthorizeResponse>(`${API_ENDPOINTS.OAUTH_AUTHORIZE}`, {
        method: "POST",
        body: {
          client_id: clientId.value,
          redirect: redirectUri.value,
          user_id: userId.value,
          response_type: "code",
        },
      });

      if (response.code === 200) {
        isSuccess.value = true;
        const redirectUrl = `${response.data.redirect}?code=${response.data.code}`;
        window.location.href = redirectUrl;
      } else {
        error.value = response.data.message || "Approval failed. Please try again.";
      }
    } catch (err) {
      error.value = "An error occurred during approval. Please try again.";
    } finally {
      isLoading.value = false;
    }
  };

  // Handle denial logic
  const deny = (): void => {
    window.location.href = `${FRONTEND_URL}`; 
  };

  return {
    clientId,
    redirectUri,
    userId,
    isLoading,
    isSuccess,
    error, 
    initialize,
    checkUserAuthentication,
    approveAuthorization,
    deny,
  };
};