import { useCookie } from "#app";
import { API_ENDPOINTS } from "~/config/api";

export const useAuth = () => {
  const user = useState("user", () => null);
  const token = useCookie("auth_token", { maxAge: 60 * 60 * 24 * 7 });
  // Option 1: Using composable directly
  const { add: notify } = useNotification()
  // Option 2: Using global $notify (from our plugin)
  // const { $notify } = useNuxtApp()

  const signin = async (credentials: { email: string; password: string }) => {
    const response = await $http<{ access_token: string, message: string }>(`${API_ENDPOINTS.LOGIN}`, {
      method: "POST",
      body: credentials,
    });
    
    if (response.error) {
      notify('Signin failed', 'error');
      return { error: response.error };
    }

    if (response.data?.access_token) {
      token.value = response.data.access_token;
      notify(response.message, 'success');
      return { data: response.data };
    }
    
    return { error: { message: "Invalid response from server" } };
  };

  const signup = async (credentials: { account_type: string; first_name: string; last_name: string; email: string; phone: string }) => {
    const response = await $http<{ access_token: string }>(`${API_ENDPOINTS.REGISTER}`, {
      method: "POST",
      body: credentials,
    });

    if (response.error) {
      notify('Login failed', 'error');
      return { error: response.error };
    }

    if (response.data) {
      notify('Login successful', 'success');
      return { data: response };
    }
    return { error: { message: "Invalid response from server" } };
    }

  const logout = async () => {
    const response = await $http(`${API_ENDPOINTS.LOGOUT}`, { method: "POST" });
    token.value = null;
    user.value = null;
  };

  const fetchCurrentUser = async () => {
    if (!token.value) {
      user.value = null;
      return;
    }
    const response = await $http(`${API_ENDPOINTS.CURRENT_USER}`);
    
    if (response.error) {
      user.value = null;
    } else {
      user.value = response.data as typeof user.value;
    }
  };

  return { 
    user, 
    signin,
    signup, 
    logout, 
    fetchCurrentUser, 
    isAuthenticated: computed(() => !!user.value) 
  };
};
