<template>
  <div class="flex items-center justify-center mt-6 mb-6">
    <div
      class="w-full max-w-md bg-white shadow-md border border-gray-200 rounded-lg p-4 shadow-t-lg"
    >
      <h1 class="text-center text-2xl font-semibold text-gray-900 mb-4">
        Sign in to your account
      </h1>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div class="mb-4">
          <UIInputField
            id="login"
            :label="usePhone ? 'Mobile Number' : 'Email'"
            :type="usePhone ? 'tel' : 'email'"
            :placeholder="usePhone ? 'i.e. 0177XXXXX' : 'i.e. yousuf@gmail.com'"
            v-model="loginInput"
            :errorMessage="errors.login ? errors.login[0] : ''"
            required
          />
        </div>
        <div class="flex justify-end mb-2">
          <button
            type="button"
            @click="toggleLoginMethod"
            class="text-sm text-fuchsia-900 cursor-pointer hover:underline"
          >
            {{ usePhone ? "Use Email Instead" : "Use Phone Instead" }}
          </button>
        </div>
        <div class="mb-4 relative">
          <UIInputField
            id="password"
            label="Password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="Enter your password"
            v-model="password"
            :errorMessage="errors.password ? errors.password[0] : ''"
            required
          />
          <button
            type="button"
            @click="togglePasswordVisibility"
            class="absolute top-8 right-2 text-fuchsia-500 hover:text-fuchsia-800 focus:outline-none"
          >
            <Icon
              :name="showPassword ? 'heroicons:eye' : 'heroicons:eye-slash'"
              class="w-5 h-5"
            />
          </button>
        </div>

        <UIButton
          :buttonText="'Sign In'"
          class="cursor-pointer"
          :isLoading="isLoading"
          :isSuccess="isSuccess"
          type="submit"
        />
        <p class="text-center text-gray-500 text-sm mt-4">
          Not a member yet?
          <NuxtLink
            :to="`/signup?client_id=${$route.query.client_id}&redirect=${$route.query.redirect}`"
            class="text-fuchsia-900 cursor-pointer hover:underline"
          >
            Sign Up
          </NuxtLink>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
useHead({ title: "Signin" });
definePageMeta({ middleware: ["guest"] });

const loginInput = ref("");
const password = ref("");
const errors = ref({});
const usePhone = ref(false);
const showPassword = ref(false);

const isLoading = ref(false);
const isSuccess = ref(false);

const { signin } = useAuth();
const route = useRoute();

const toggleLoginMethod = () => {
  usePhone.value = !usePhone.value;
  loginInput.value = "";
};

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const handleLogin = async () => {
  isLoading.value = true;
  isSuccess.value = false;
  errors.value = {};

  const credentials = usePhone.value
    ? { phone: loginInput.value, password: password.value }
    : { email: loginInput.value, password: password.value };

  const response = await signin(credentials);

  if (response.error) {
    errors.value = response.error.details || {};
    isLoading.value = false;
    return;
  }

  const redirectUrl = new URLSearchParams(window.location.search).get("redirect");
  
  if (redirectUrl) {
    const authCode = await $http(`oauth/authorize`, {
      method: "POST",
      body: {
        client_id: route.query.client_id,
        redirect: redirectUrl,
        user_id: response.data.user.id,
        response_type: "code",
      },
    });

    if (authCode.error) {
      errors.value =
        authCode.error.message || "Approval failed. Please try again.";
      isLoading.value = false;
      return;
    }
    
    isSuccess.value = true;
    window.location.href = `${authCode.data.redirect}?code=${authCode.data.code}`;
    return;
  } else {
    window.location.href = "/dashboard";
  } 
  
  if(redirectUrl) {
    isSuccess.value = true;
    window.location.href = route.query.redirect || "/dashboard";
  }
};
</script>

<style scoped></style>
