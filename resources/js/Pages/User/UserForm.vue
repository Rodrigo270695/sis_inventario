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
    user: Object,
    pdvs: Array,
    roles: Array,
});

const form = useForm({
    id: props.user ? String(props.user.id) : "",
    name: props.user ? props.user.name : "",
    dni: props.user ? props.user.dni : "",
    email: props.user ? props.user.email : "",
    pdv_id: props.user ? props.user.pdv.id : "",
    rol: props.user ? props.user.roles[0].name : "",
});

const submit = () => {
    if (props.user) {
        form.put(route("user.update", props.user.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("user.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const emit = defineEmits(["close-modal"]);
</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Usuario' : 'Actualizar Usuario'" @close-modal="emit('close-modal')" />
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Nombre Completo" />
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                            @input="form.name = toTitleCase(form.name)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="DNI" />
                        <TextInput class="w-full" v-model="form.dni" />
                        <InputError class="w-full" :message="form.errors.dni" />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Correo" />
                        <TextInput type="email" class="w-full" v-model="form.email" />
                        <InputError
                            class="w-full"
                            :message="form.errors.email"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Punto de venta" />
                        <select
                            id="select"
                            v-model="form.pdv_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="pdv in pdvs"
                                :key="pdv.id"
                                :value="pdv.id"
                            >
                                {{ pdv.zonal.nombre }}->{{ pdv.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.pdv_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Rol" />
                        <select
                            id="select"
                            v-model="form.rol"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="rol in roles"
                                :key="rol.id"
                                :value="rol.name"
                            >
                                {{ rol.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.rol"
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
