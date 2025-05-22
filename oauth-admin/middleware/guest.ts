export default defineNuxtRouteMiddleware(async () => {
	const data = useAuth();
	if (data.user.value) return navigateTo('/dashboard', { replace: true });
});
