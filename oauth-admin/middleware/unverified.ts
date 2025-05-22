export default defineNuxtRouteMiddleware(() => {
	const data = useAuth();
	if (!data.user.value) return navigateTo('/')

});
