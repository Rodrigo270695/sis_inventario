<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    solicitude: Object,
    types: Array,
});

const form = useForm({
    id: props.solicitude ? props.solicitude.id : "",
    mensaje: props.solicitude ? props.solicitude.mensaje : "",
    type_request_id: props.solicitude ? props.solicitude.type_request_id : "",
    observacion_local: props.solicitude
        ? props.solicitude.observacion_local
        : "",
    observacion_gerencia: props.solicitude
        ? props.solicitude.observacion_gerencia
        : "",
});

const submit = () => {
    if (props.solicitude) {
        form.put(route("solicitude.update", props.solicitude.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("solicitude.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div
            class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center"
        >
            {{ form.id == 0 ? "Registrar Solicitud" : "Actualizar Solicitud" }}
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
                        <InputLabel value="Tipo" />
                        <select
                            id="select"
                            :disabled="
                                $page.props.user.roles.includes(
                                    'Supervisor 1'
                                ) ||
                                $page.props.user.roles.includes('Aprobador')
                            "
                            v-model="form.type_request_id"
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
                            :message="form.errors.type_request_id"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Mensaje" />
                        <TextArea
                            :disabled="
                                ($page.props.user.roles.includes(
                                    'Supervisor 1'
                                ) ||
                                $page.props.user.roles.includes('Aprobador') ||
                                (props.solicitude?.aprobacion_local !== 0 )) && props.solicitude?.id !== undefined
                            "
                            class="w-full h-40"
                            v-model="form.mensaje"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.mensaje"
                        />
                    </div>
                    <div
                        v-if="$page.props.user.roles.includes('Supervisor 1')"
                        class="col-span-6 sm:col-span-6"
                    >
                        <InputLabel value="Observación" />
                        <TextArea
                            :disabled="props.solicitude?.aprobacion_local !== 0"
                            class="w-full h-40"
                            v-model="form.observacion_local"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.observacion_local"
                        />
                    </div>
                    <div
                        v-if="
                            $page.props.user.roles.includes('Administrador') ||
                            $page.props.user.roles.includes('Aprobador')
                        "
                        class="col-span-6 sm:col-span-6"
                    >
                        <InputLabel value="Observación" />
                        <TextArea
                            :disabled="
                                props.solicitude.aprobacion_gerencia !== 0
                            "
                            class="w-full h-40"
                            v-model="form.observacion_gerencia"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.observacion_gerencia"
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
