export async function $http<T>(
  url: string,
  options: any = {}
): Promise<{ data?: T; error?: any }> {
  const token = useCookie("access_token");
  const config = useRuntimeConfig().public;

  // Default to 'api' if not specified
  const baseType = options.base || "api";
  const baseUrl =
    baseType === "service" ? config.serviceURL : config.apiURL;

  const fullUrl = `${baseUrl}${url}`;
  const isFormData = options.body instanceof FormData;

  try {
    return await $fetch<T>(fullUrl, {
      ...options,
      headers: {
        ...options.headers,
        Authorization: token.value ? `Bearer ${token.value}` : "",
        ...(isFormData ? {} : { "Content-Type": "application/json" }),
      },
    });
  } catch (error: any) {
    return {
      error: {
        status: error.response?.status || 500,
        message: error.response?._data?.message || "Something went wrong",
        details: error.response?._data?.errors || null,
      },
    };
  }
}
