<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    zonal: Object,
});

const form = useForm({
    id: props.zonal ? props.zonal.id : "",
    nombre: props.zonal ? props.zonal.nombre : "",
    unidad_negocio: props.zonal ? props.zonal.unidad_negocio : "",
});

const submit = () => {
    if (props.zonal) {
        form.put(route("zonal.update", props.zonal.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("zonal.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const datos = [
    { id: 1, name: "DISTRIBUIDORA" },
    { id: 2, name: "FRANQUICIA" },
    { id: 3, name: "DAM" },
    { id: 4, name: "PROACTIVO" },
    { id: 5, name: "REACTIVO" },
];
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Zonal" : "Actualizar Zonal" }}
        </div>
        <button @click="emit('close-modal')">
            <v-icon
                class="text-white rounded-md bg-red-400"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Unidad de negocio" />
                        <select
                            id="select"
                            v-model="form.unidad_negocio"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="dato in datos"
                                :key="dato.id"
                                :value="dato.name"
                            >
                                {{ dato.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.unidad_negocio"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" />
                        <TextInput
                            class="w-full"
                            v-model="form.nombre"
                            @input="form.nombre = form.nombre.toUpperCase()"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.nombre"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-sky-800 hover:bg-sky-700 text-white px-4 py-2 rounded-md mr-2"
                    :disabled="form.processing"
                >
                    {{ form.id == 0 ? "Registrar" : "Actualizar" }}
                </button>
            </div>
        </div>
    </form>
</template>
