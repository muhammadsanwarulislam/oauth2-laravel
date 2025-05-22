export function useOAuth() {
    const clientId = ref("");
    const redirectUri = ref("");
    const name = ref("");
    const secret = ref("");

    const OAUTH_CLIENT_ID = useRuntimeConfig().public.oauthClientId;
    const OAUTH_CLIENT_CALLBACK = useRuntimeConfig().public.oauthClientCallback;
    const FRONTEND_REDIRECT_URL = useRuntimeConfig().public.frontendRedirectURL;

    const retrieveClientData = async () => {
        try {
            const response: any = await $http(`oauth/authorize`, {
                method: "GET",
                params: {
                    client_id: OAUTH_CLIENT_ID,
                    response_type: "code",
                },
            });

            clientId.value = response.data.client.id;
            redirectUri.value = response.data.client.redirect;
            name.value = response.data.client.name;
            secret.value = response.data.client.secret;
        } catch (error) {
            console.error("Error during retrieval:", error);
        }
    };

    const processAuthorizationCodeToGenerateAccessToken = async (code: string) => {
        try {
            const response: any = await $http(`oauth/get-token`, {
                method: "POST",
                body: {
                    client_id: OAUTH_CLIENT_ID,
                    client_secret: secret,
                    code: code,
                    grant_type: "authorization_code"
                },
            });

            const accessToken = response.data.token.access_token;
            const expiresAtString = response.data.token.expires_at;
            // Convert "expires_at" to timestamp in milliseconds
            const expiresAtTime = new Date(expiresAtString).getTime();
            const currentTime = Date.now();

            // Calculate maxAge in seconds
            const maxAgeInSeconds = Math.floor((expiresAtTime - currentTime) / 1000);

            // Set cookie with maxAge
            useCookie('access_token', { maxAge: maxAgeInSeconds }).value = accessToken;
            useCookie('expires_at').value = expiresAtString;

            return accessToken;
        } catch (error) {
            throw error;
        }
    };


    const fetchUserData = async () => {
        try {
            const userResponse: any = await $http('oauth-current-user', {
                method: "GET",
            }
            );
            //console.log(userResponse);
            const userData = userResponse.data;
            return userData;
        } catch (error) {
            throw error;
        }
    };

    const redirectToAuthorization = () => {
        if(!OAUTH_CLIENT_ID || !OAUTH_CLIENT_CALLBACK) return;
        const authorizationUrl = `${FRONTEND_REDIRECT_URL}?client_id=${OAUTH_CLIENT_ID}&redirect=${OAUTH_CLIENT_CALLBACK}`;
        window.location.href = authorizationUrl;
    };

    const logout = () => {
        useCookie('access_token').value = null;
        navigateTo("/");
    };

    return {
        clientId,
        redirectUri,
        name,
        secret,
        retrieveClientData,
        processAuthorizationCodeToGenerateAccessToken,
        fetchUserData,
        redirectToAuthorization,
        logout,
    };
}