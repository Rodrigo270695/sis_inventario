<template>
    <div class="flex justify-between">
        <h2 class="font-bold text-xl text-slate-500">
            {{ title }}
        </h2>
        <button class="bg-green-100 hover:bg-green-200 w-12 rounded-md shadow-abajo-1" @click="$emit('reload')">
            <v-icon class="text-slate-500" name="io-reload-circle-sharp" scale="1.7" />
        </button>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true
    }
});
</script>
