<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    pdv: Object,
});

const form = useForm({
    pdv_id: props.pdv ? props.pdv.id : "",
    nombre: '' ,
    descripcion: '',
});

const submit = () => {

    form.post(route("store.createstore"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });

};

const emit = defineEmits(["close-modal"]);
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg text-slate-700 font-bold inline-flex items-center">
            Almacén para {{ props.pdv ? props.pdv.nombre : '' }}
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
                        <InputLabel value="Nombre" />
                        <TextInput
                            class="w-full"
                            v-model="form.nombre"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.nombre"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" />
                        <TextArea class="w-full" v-model="form.descripcion" />
                        <InputError
                            class="w-full"
                            :message="form.errors.descripcion"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-sky-800 hover:bg-sky-700 text-white px-4 py-2 rounded-md mr-2"
                    :disabled="form.processing"
                >
                    Registrar
                </button>
            </div>
        </div>
    </form>
</template>
