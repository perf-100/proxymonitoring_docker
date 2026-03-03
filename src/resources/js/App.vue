<template>
  <div>
    <Header />

    <Alert />

    <main class="container">
      <router-view />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import Header from './components/Header.vue'
import Alert from './components/ui/Alert.vue'
import Footer from './components/Footer.vue'
</script>