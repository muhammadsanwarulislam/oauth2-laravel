<template>
  <div class="flex items-center justify-center mt-6 mb-6">
    <div
      class="w-full max-w-md bg-white shadow-md border border-gray-200 rounded-lg p-4 shadow-t-lg"
    >
      <h2 class="text-center text-2xl font-semibold text-gray-900 mb-4">
        Sign up for new account
      </h2>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div class="mt-2">
          <UIInputField
            id="accountType"
            label="Account Type"
            type="radio"
            :options="[
              { label: 'Individual', value: 'individual' },
              { label: 'Company', value: 'company' },
            ]"
            :modelValue="selectedAccountType"
            @update:modelValue="selectedAccountType = $event"
            required
          />
        </div>
        <div class="mt-2">
          <UIInputField
            id="firstname"
            :label="
              selectedAccountType === 'company' ? 'Company Name' : 'First Name'
            "
            type="text"
            :placeholder="
              selectedAccountType === 'company'
                ? 'i.e. Pran RFL'
                : 'i.e. Mohammad'
            "
            v-model="first_name"
            :errorMessage="errors.first_name ? errors.first_name[0] : ''"
            required
          />
        </div>
        <div class="mt-2">
          <UIInputField
            id="lastname"
            :label="
              selectedAccountType === 'company'
                ? 'Representative Name'
                : 'Last Name'
            "
            type="text"
            :placeholder="
              selectedAccountType === 'company'
                ? 'i.e. Yousuf Hossain'
                : 'i.e. Yousuf'
            "
            v-model="last_name"
            :errorMessage="errors.last_name ? errors.last_name[0] : ''"
            required
          />
        </div>
        <div class="flex space-x-4 mt-2">
          <UIInputField
            id="countryCode"
            label="Country Code"
            :isSelect="true"
            v-model="selectedCountryCode"
            :options="countryCodeOptions"
            placeholder="Select Code"
            required
          />
          <UIInputField
            id="phone"
            label="Mobile Number"
            v-model="phone"
            type="text"
            placeholder="i.e. 177XXXXX"
            :errorMessage="errors.phone ? errors.phone[0] : ''"
            :isSelect="false"
            required
          />
        </div>
        <div class="mt-2">
          <UIInputField
            id="email"
            label="Email(Optional)"
            type="email"
            placeholder="i.e. mohammad.yousuf@gmail"
            v-model="email"
            :errorMessage="errors.email ? errors.email[0] : ''"
          />
        </div>

        <UIButton
          :buttonText="'Sign Up'"
          :isLoading="isLoading"
          :isSuccess="isSuccess"
          type="submit"
        />
        <p class="mt-4 text-center">
          Already have an account?
          <router-link
            :to="`/signin?client_id=${$route.query.client_id}&redirect=${$route.query.redirect}`"
            class="text-fuchsia-900 hover:underline"
            >Login</router-link
          >
        </p>
      </form>
      <CommonOtpModal
        v-if="showOtp"
        :email="email"
        :phone="phone"
        @verified="onOtpVerified"
      />
    </div>
  </div>
</template>

<script setup>
useHead({ title: "Signup" });
definePageMeta({ middleware: ["guest"] });

const { signup } = useAuth();

const selectedAccountType = ref("individual");
const first_name = ref("Muhammad");
const last_name = ref("Yousuf");
const email = ref("super01@gmail.com");
const phone = ref("");
const selectedCountryCode = ref("+880");
const countryCodeOptions = ref([
  { label: "Bangladesh (+880)", value: "+880" },
  { label: "USA (+1)", value: "+1" },
  { label: "UK (+44)", value: "+44" },
  { label: "India (+91)", value: "+91" },
  { label: "Australia (+61)", value: "+61" },
  { label: "Canada (+1)", value: "+1" },
  { label: "Germany (+49)", value: "+49" },
  { label: "France (+33)", value: "+33" },
  { label: "Italy (+39)", value: "+39" },
  { label: "Spain (+34)", value: "+34" },
  { label: "Portugal (+351)", value: "+351" },
  { label: "Brazil (+55)", value: "+55" },
  { label: "Argentina (+54)", value: "+54" },
  { label: "Mexico (+52)", value: "+52" },
  { label: "Colombia (+57)", value: "+57" },
  { label: "Peru (+51)", value: "+51" },
  { label: "Chile (+56)", value: "+56" },
  { label: "Ecuador (+593)", value: "+593" },
  { label: "Venezuela (+58)", value: "+58" },
  { label: "Paraguay (+595)", value: "+595" },
  { label: "Uruguay (+598)", value: "+598" },
  { label: "Bolivia (+591)", value: "+591" },
  { label: "Guyana (+592)", value: "+592" },
  { label: "Suriname (+597)", value: "+597" },
  { label: "Honduras (+504)", value: "+504" },
  { label: "Nicaragua (+505)", value: "+505" },
  { label: "Costa Rica (+506)", value: "+506" },
  { label: "Panama (+507)", value: "+507" },
  { label: "El Salvador (+503)", value: "+503" },
  { label: "Guatemala (+502)", value: "+502" },
  { label: "Haiti (+509)", value: "+509" },
  { label: "Dominican Republic (+1-809)", value: "+1-809" },
  { label: "Jamaica (+1-876)", value: "+1-876" },
]);
const showOtp = ref(false);
const errors = ref({});

const isLoading = ref(false);
const isSuccess = ref(false);

const handleRegister = async () => {
  isLoading.value = true;
  isSuccess.value = false;
  errors.value = {};

  const credentials = {
    account_type: selectedAccountType.value,
    first_name: first_name.value,
    last_name: last_name.value,
    email: email.value,
    phone: `${phone.value}`,
  };
  const response = await signup(credentials);

  if (response.error) {
    errors.value = response.error.details || {};
    isLoading.value = false;
    isSuccess.value = false;
    return false;
  }

  if (response.data) {
    isLoading.value = false;
    isSuccess.value = true;
    showOtp.value = true;

    localStorage.setItem("otp_pending", "true");
    localStorage.setItem("otp_email", email.value);
    localStorage.setItem("otp_phone", phone.value);
  }
};

const onOtpVerified = () => {
  const redirectUrl = new URLSearchParams(window.location.search).get(
    "redirect"
  );
  const clientId = new URLSearchParams(window.location.search).get("client_id");

  localStorage.removeItem("otp_pending");
  localStorage.removeItem("otp_email");
  localStorage.removeItem("otp_phone");

  showOtp.value = false;

  // navigateTo('/signin?client_id=' + clientId + '&redirect=' + redirectUrl)
  navigateTo(
    `/set-password?client_id=${clientId}&redirect=${redirectUrl}&email=${email.value}`
  );
};

onMounted(() => {
  if (localStorage.getItem("otp_pending") === "true") {
    email.value = localStorage.getItem("otp_email") || "";
    phone.value = localStorage.getItem("otp_phone") || "";
    showOtp.value = true;
  }
});
</script>

<style scoped></style>
