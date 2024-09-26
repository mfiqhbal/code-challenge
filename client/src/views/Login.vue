<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
      <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block text-gray-700" for="email">Email</label>
          <input v-model="email" class="mt-1 block w-full p-2 border border-gray-300 rounded" type="email" id="email" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700" for="password">Password</label>
          <input v-model="password" class="mt-1 block w-full p-2 border border-gray-300 rounded" type="password" id="password" required />
        </div>
        <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600" type="submit">Login</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useToast } from "vue-toastification";

export default {
  name: 'Login',
  setup() {
    const toast = useToast();
    return { toast }
  },
  data() {
    return {
      email: '',
      password: '',

    };
  },
  methods: {
    async login() {

        try {
            const response = await axios.post('/api/login', {email: this.email, password: this.password});
            console.log('Response:', response);
            localStorage.setItem('token', response.data.data.token);
            this.toast.success('Login successful!');
            this.$router.push('/dashboard');
            // window.location.reload();
        } catch (error) {
            console.error('Error uploading file:', error);
        }
        
    }
  }
}
</script>

<style scoped>
/* Add any additional styles here */
</style>
