<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import ZonalForm from "./ZonalForm.vue";
import { useForm } from "@inertiajs/vue3";

let zonalObj = ref(null);
let showModal = ref(false);

const props = defineProps({
    zonals: Array,
});

const form = useForm({
    zonal: Object,
});

const addZonal = () => {
    zonalObj.value = null;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    zonalObj.value = null;
};

// Detectar la tecla ESC para cerrar el modal
const onKeydown = (e) => {
    if (e.key === "Escape") {
        closeModal();
    }
};

onMounted(() => {
    window.addEventListener("keydown", onKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", onKeydown);
});
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="pt-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div
                        class="bg-slate-300 text-xl sm:text-2xl px-4 h-11 inline-flex items-center w-full text-slate-700 font-bold"
                    >
                        <h2>Gestionar Zonal</h2>
                    </div>

                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-auto lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                placeholder="Buscar Zonal"
                                @input="query = query.toUpperCase()"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="goToUserIndex"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-800 rounded-e-md hover:bg-sky-700"
                            >
                                <v-icon
                                    name="io-reload-circle-sharp"
                                    scale="1.5"
                                />
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-sky-800 hover:bg-sky-700 p-2 text-white rounded-lg flex items-center"
                                @click="addZonal"
                            >
                                <v-icon name="io-add-circle-sharp" />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-slate-300">
                                    <tr class="">
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs sm:text-base font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Unidad de Negocio
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs sm:text-base font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Nombre
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs sm:text-base font-semibold text-gray-600 uppercase tracking-wider"
                                        >
                                            Estado
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="zonal in zonals.data"
                                        :key="zonal.id"
                                        class="hover:bg-slate-500 hover:text-white"
                                    >
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap "
                                        >
                                            {{ zonal.unidad_negocio }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ zonal.nombre }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        zonal.estado == 1,
                                                    'bg-red-500 rounded text-white':
                                                        zonal.estado == 0,
                                                }"
                                            >
                                                {{
                                                    zonal.estado == 1
                                                        ? "ACTIVO"
                                                        : "INACTIVO"
                                                }}
                                            </p>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                        ></td>
                                    </tr>
                                    <tr v-if="zonals.data.length <= 0">
                                        <td
                                            class="text-center text-slate-800 text-md sm:text-lg font-semibold bg-slate-300"
                                            colspan="5"
                                        >
                                            No hay registros
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-2" :pagination="zonals" />
                        </div>
                    </div>
                    <Modal :show="showModal">
                        <ZonalForm @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
