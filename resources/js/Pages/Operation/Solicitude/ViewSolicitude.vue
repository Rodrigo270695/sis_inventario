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

const emit = defineEmits(["close-modal"]);
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div
            class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center"
        >
            Ver solicitud
        </div>
        <button @click="emit('close-modal')">
            <v-icon
                class="text-white rounded-md bg-red-400"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>

    <div class="bg-white shadow-md rounded-md p-4">
        <div class="flex flex-col">
            <div class="flex flex-col">
                <div
                    class="flex flex-col space-y-4 bg-gray-100 p-4 rounded-lg shadow"
                >
                    <div
                        class="p-4 bg-white rounded-md shadow-sm border-l-4 border-blue-500"
                    >
                        <h3 class="text-lg font-bold text-gray-700">
                            Detalles de la Solicitud
                        </h3>
                        <div class="mt-2 text-gray-600">
                            <p>

                                <strong>ID de Solicitud:</strong>
                                {{ solicitude?.id }}
                            </p>
                            <p>
                                <strong>Tipo de Solicitud:</strong>
                                {{ solicitude?.typerequest.nombre }}
                            </p>
                            <p>
                                <strong>Mensaje:</strong>
                                <p>
                                    {{ solicitude?.mensaje }}
                                </p>
                            </p>
                        </div>
                    </div>
                    <div
                        class="p-4 bg-white rounded-md shadow-sm border-l-4 border-yellow-500"
                    >
                        <h3 class="text-lg font-bold text-gray-700">
                            Observaciones
                        </h3>
                        <div class="mt-2 text-gray-600">
                            <p>
                                <strong>Local:</strong>
                                <p>
                                    {{ solicitude?.observacion_local }}
                                </p>
                            </p>
                            <p>
                                <strong>Gerencia:</strong>
                                <p>
                                    {{ solicitude?.observacion_gerencia }}
                                </p>
                            </p>
                        </div>
                    </div>
                    <div
                        class="p-4 bg-white rounded-md shadow-sm border-l-4 border-green-500"
                    >
                        <h3 class="text-lg font-bold text-gray-700">
                            Aprobaciones
                        </h3>
                        <div class="mt-2 text-gray-600">
                            <p>
                                <strong>Local:</strong>
                                <span
                                    :class="{
                                        'text-green-500':
                                            solicitude?.aprobacion_local === 1,
                                        'text-orange-500':
                                            solicitude?.aprobacion_local === 0,
                                        'text-red-500':
                                            solicitude?.aprobacion_local === 2,
                                    }"
                                >
                                    {{
                                        solicitude?.aprobacion_local === 1
                                            ? " Aprobado"
                                            : solicitude?.aprobacion_local === 0
                                            ? " Pendiente"
                                            : " Rechazado"
                                    }}
                                </span>
                            </p>
                            <p>
                                <strong>Gerencia:</strong>
                                <span
                                    :class="{
                                        'text-green-500':
                                            solicitude?.aprobacion_gerencia ===
                                            1,
                                        'text-orange-500':
                                            solicitude?.aprobacion_gerencia ===
                                            0,
                                        'text-red-500':
                                            solicitude?.aprobacion_gerencia ===
                                            2,
                                    }"
                                >
                                    {{
                                        solicitude?.aprobacion_gerencia === 1
                                            ? " Aprobado"
                                            : solicitude?.aprobacion_gerencia ===
                                              0
                                            ? " Pendiente"
                                            : " Rechazado"
                                    }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
