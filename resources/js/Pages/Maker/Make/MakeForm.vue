<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
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
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Marca" : "Actualizar Marca" }}
        </div>
        <button @click="emit('close-modal')">
            <v-icon
                class="text-white rounded-md bg-red-400"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>

    <div class="mt-2">
        <a
            @click="toggleForm"
            class="text-slate-700 py-2 px-4 rounded-md font-semibold hover:text-sky-700 cursor-pointer"
        >
            ¿Quieres agregar un Tipo de equipo ?
        </a>
        <div v-if="showType" class="mx-4 mt-2">
            <form @submit.prevent="addType">
                <div class="grid grid-cols-4">
                    <div class="col-span-3 sm:col-span-3">
                        <InputLabel value="Nombre" />
                        <TextInput class="w-full" v-model="type.nombre" />
                        <InputError
                            class="w-full"
                            :message="type.errors.nombre"
                        />
                    </div>
                    <div class="col-span-1 sm:col-span-1">
                        <button
                            class="bg-sky-500 hover:bg-sky-400 text-white rounded-md w-[65px] ml-3 mt-5 py-2"
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
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Tipo" />
                        <select
                            id="select"
                            v-model="form.equipment_type_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="type in props.types"
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
                        <InputLabel value="Nombre" />
                        <TextInput class="w-full" v-model="form.nombre" />
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
