<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    pdv: Object,
    zonals: Array,
});

const form = useForm({
    id: props.pdv ? String(props.pdv.id) : "",
    zonal_id: props.pdv ? props.pdv.zonal_id : "",
    nombre: props.pdv ? props.pdv.nombre : "",
    direccion: props.pdv ? props.pdv.direccion : "",
});

const submit = () => {
    if (props.pdv) {
        form.put(route("pdv.update", props.pdv), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("pdv.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar PDV' : 'Actualizar PDV'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Zonal" required/>
                        <select
                            v-model="form.zonal_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="zonal in zonals"
                                :key="zonal.id"
                                :value="zonal.id"
                            >
                                {{ zonal.unidad_negocio }}-{{ zonal.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.zonal_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" required/>
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
                        <InputLabel value="Dirección"/>
                        <TextArea
                            class="w-full h-28"
                            v-model="form.direccion"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.direccion"
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
