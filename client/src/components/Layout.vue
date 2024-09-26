<template>
    <div>
        <header class="header">
            <h1>My Application</h1>
            <button @click="logout" class="logout-button">Logout</button>
        </header>
        <router-view />
    </div>
</template>

<script>
export default {
    methods: {
        logout() {
            // Implement logout logic here
            localStorage.removeItem('token');
            this.$router.push('/login');
        }
    }
}
</script>

<style>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #f8f9fa;
}

.logout-button {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}
</style>