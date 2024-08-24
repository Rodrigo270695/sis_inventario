<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TextArea from "@/Components/TextArea.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    typer: Object,
});

const form = useForm({
    id: props.typer ? String(props.typer.id) : "",
    nombre: props.typer ? props.typer.nombre : "",
    descripcion: props.typer ? props.typer.descripcion : "",
});

const submit = () => {
    if (props.typer) {
        form.put(route("typer.update", props.typer.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else { 
        form.post(route("typer.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Tipo' : 'Actualizar Tipo'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" required />
                        <TextInput
                            class="w-full"
                            v-model="form.nombre"
                            @input="form.nombre = toTitleCase(form.nombre)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.nombre"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" />
                        <TextArea
                            class="w-full h-28"
                            v-model="form.descripcion"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.descripcion"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
