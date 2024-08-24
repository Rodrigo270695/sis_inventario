<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";

const props = defineProps({
    make: Object,
    types: Array,
});

const form = useForm({
    id: props.make ? props.make.id : "",
    equipment_type_id: props.make ? props.make.equipment_type_id : "",
    nombre: props.make ? props.make.nombre : "",
});

const type = useForm({
    nombre: "",
});

const showType = ref(false);

const submit = () => {
    if (props.make) {
        form.put(route("make.update", props.make.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("make.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const toggleForm = () => {
    showType.value = !showType.value;
};

const addType = () => {
    type.post(route("type.createstore"), {
        onSuccess: () => {
            type.reset("nombre");
        },
    });
};

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Marca' : 'Actualizar Marca'" @close-modal="emit('close-modal')" />
    <div class="pt-2 bg-3D-50">
        <a
            @click="toggleForm"
            class="text-slate-500 py-2 px-4 rounded-md font-semibold hover:text-sky-900 cursor-pointer"
        >
            ¿Quieres agregar un Tipo de equipo ?
        </a>
        <div v-if="showType" class="mx-4 mt-2 border-b-2 border-slate-300">
            <form @submit.prevent="addType">
                <div class="grid grid-cols-4">
                    <div class="col-span-3 sm:col-span-3">
                        <InputLabel value="Nombre" />
                        <TextInput class="w-full" v-model="type.nombre"
                            @input="type.nombre = toTitleCase(type.nombre)"
                        />
                        <InputError
                            class="w-full"
                            :message="type.errors.nombre"
                        />
                    </div>
                    <div class="col-span-1 sm:col-span-1">
                        <button
                            class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 rounded-md mr-2 shadow-abajo-1 w-[65px] ml-3 mt-5 py-2 mb-2"
                            :disabled="form.processing"
                        >
                            <v-icon name="md-addcircle-round" scale="1.2" />
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Tipo" required/>
                        <select
                            v-model="form.equipment_type_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="type in types"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{ type.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.equipment_type_id"
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
