<template>
    <select
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :class="customClass"
    >
        <option disabled selected value="">
            {{ placeholder }}
        </option>
        <option
            v-for="option in options"
            :key="option.id"
            :value="name === true ? option.name: option.id"
        >
            {{ option.name }}
        </option>
    </select>
</template>

<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true
    },
    options: {
        type: Array,
        required: true
    },
    placeholder: {
        type: String,
        default: 'Elija una opción'
    },
    customClass: {
        type: String,
        default: 'bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500'
    },
    name: {
        type: Boolean,
        default: true
    }
});
</script>
