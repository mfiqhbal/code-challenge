<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
      <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
      <form>
        <div class="mb-4">
          <label class="block text-gray-700" for="email">Email</label>
          <input class="mt-1 block w-full p-2 border border-gray-300 rounded" type="email" id="email" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700" for="password">Password</label>
          <input class="mt-1 block w-full p-2 border border-gray-300 rounded" type="password" id="password" required />
        </div>
        <button class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600" type="submit">Login</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
}
</script>

<style scoped>
/* Add any additional styles here */
</style>
