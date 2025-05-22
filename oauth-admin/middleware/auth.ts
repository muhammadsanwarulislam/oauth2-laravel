export default defineNuxtRouteMiddleware(async () => {
	const { fetchCurrentUser } = useAuth();
	const data = useAuth();
	  if (!data.user.value) {
		await fetchCurrentUser();
	  }
	if (!data.user.value) return navigateTo('/signin', { replace: true });
});
