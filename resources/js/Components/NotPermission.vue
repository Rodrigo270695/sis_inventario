<template>
    <div class="flex flex-col justify-center items-center h-96 bg-gray-200">
        <h1 class="text-4xl mb-4">No tienes permisos para esta vista</h1>
        <button @click="goBack" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Regresar</button>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';


const router = useRouter();

const goBack = () => {
    router.back();
};


</script>


