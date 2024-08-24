<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    zonal: Object,
});

const form = useForm({
    id: props.zonal ? String(props.zonal.id) : "",
    nombre: props.zonal ? props.zonal.nombre : "",
    unidad_negocio: props.zonal ? props.zonal.unidad_negocio : "",
});

const submit = () => {
    if (props.zonal) {
        form.put(route("zonal.update", props.zonal), {
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

const datos = [
    { id: 1, name: "ADMINISTRACION" },
    { id: 2, name: "DISTRIBUIDORA" },
    { id: 3, name: "FRANQUICIA" },
    { id: 4, name: "DAM" },
    { id: 5, name: "PROACTIVO" },
    { id: 6, name: "REACTIVO" },
];

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Zonal' : 'Actualizar Zonal'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit" >
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Unidad de negocio" required/>
                        <SelectInput
                            v-model="form.unidad_negocio"
                            :options="datos"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.unidad_negocio"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" required/>
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
                <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
