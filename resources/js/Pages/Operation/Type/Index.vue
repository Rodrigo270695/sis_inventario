<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import TypeForm from "./TypeForm.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    types: Object,
    texto: String,
});

const form = useForm({
    type: Object,
});

let typeObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);


const toggleOptions = (typeId) => {
    if (openMenuId.value === typeId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = typeId;
    }
};

const addType = () => {
    typeObj.value = null;
    showModal.value = true;
};

const editType = (type) => {
    openMenuId.value = null;
    typeObj.value = type;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    typeObj.value = null;
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

const changeStatus = (type) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de del Tipo?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("typer.change", type), {
                preserveScroll: true,
            });
        }
    });
};


const deleteType = (type) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("typer.destroy", type), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("typer.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("typer.index"));
};

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="pt-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="flex justify-between font-extrabold border-b px-4 py-2" title="Refrescar la página">
                        <div
                            class="h-11 inline-flex items-center w-full"
                        >
                            <h2 class="text-xl sm:text-2xl text-slate-700">Gestionar Tipo de Solicitudes</h2>
                        </div>
                        <button class="bg-green-600 hover:bg-green-500 w-12 rounded-md" @click="goToIndex">
                            <v-icon class="text-white" name="io-reload-circle-sharp" scale="1.7"/>
                        </button>
                    </div>

                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-64 md:w-72 lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                placeholder="Buscar tipos"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="search"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-800 rounded-e-md hover:bg-sky-700"
                            >
                                <v-icon
                                    name="fa-search"
                                    scale="1.5"
                                />
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-sky-800 hover:bg-sky-700 p-2 text-white rounded-lg flex items-center"
                                @click="addType"
                            >
                                <v-icon
                                    name="io-add-circle-sharp"
                                    scale="1.1"
                                />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="hidden sm:block ">
                            <div class="overflow-x-auto rounded-lg">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-cyan-800">
                                        <tr class="">
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-left text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Descripción
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Estado
                                            </th>
                                            <th class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="type in types.data"
                                            :key="type.id"
                                            class="bg-sky-100 hover:bg-sky-200"
                                        >
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ type.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ type.descripcion }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                <p
                                                    class="inline-block px-2 rounded-full h-auto justify-center items-center text-xs md:text-sm"
                                                    :class="{
                                                        ' bg-green-500 text-white':
                                                            type.estado == 1,
                                                        'bg-red-500 rounded text-white':
                                                            type.estado == 0,
                                                    }"
                                                >
                                                    {{
                                                        type.estado == 1
                                                            ? "ACTIVO"
                                                            : "INACTIVO"
                                                    }}
                                                </p>
                                            </td>
                                            <td
                                                class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div class="flex items-center justify-center gap-x-1">
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-400 cursor-pointer"
                                                            @click="editType(type)"
                                                        >
                                                            <v-icon
                                                                name="md-modeedit-round"
                                                            />
                                                            <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                                Editar Tipo
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="text-white p-1 rounded-md"
                                                            :class="{
                                                                'bg-orange-500 hover:bg-orange-400':
                                                                    type.estado ==
                                                                    1,
                                                                'bg-green-500 hover:bg-green-400':
                                                                    type.estado ==
                                                                    0,
                                                            }"
                                                            @click="
                                                                changeStatus(type)
                                                            "
                                                        >
                                                            <v-icon
                                                                v-if="
                                                                    type.estado ==
                                                                    1
                                                                "
                                                                name="gi-cancel"
                                                            />
                                                            <v-icon
                                                                v-else
                                                                name="fa-check"
                                                            />
                                                        </button>
                                                        <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                            Cambiar estado
                                                        </span>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-500 text-white p-1 rounded-md hover:bg-red-400 cursor-pointer"
                                                            @click="
                                                                deleteType(type)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-trash"
                                                            />
                                                        </button>
                                                        <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                            Eliminar Tipo
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="types.data.length <= 0">
                                            <td
                                                class="text-center text-slate-800 text-md sm:text-lg font-semibold bg-slate-300"
                                                colspan="4"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Tarjetas -->
                        <div class="block sm:hidden">
                            <div
                                v-for="type in types.data"
                                :key="type.id"
                                class="p-4 mx-1 mt-4 bg-sky-100 hover:bg-sky-200 rounded-lg shadow-md relative"
                            >
                                <!-- Contenido de la tarjeta -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg
                                        class="h-6 w-6 text-sky-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7h18M3 12h18m-9 5h9"
                                        />
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-900">
                                        Nombre:
                                        <span class="font-normal">{{
                                            type.nombre
                                        }}</span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md">
                                    <p>
                                        <strong>Descripción:</strong>
                                        <span class="text-gray-700 ml-1">{{
                                            type.descripcion
                                        }}</span>
                                    </p>
                                    <p
                                        :class="{
                                            'text-green-500': type.estado == 1,
                                            'text-red-500': type.estado == 0,
                                        }"
                                        class="flex items-center"
                                    >
                                        <svg
                                            :class="{
                                                'text-green-500':
                                                    type.estado == 1,
                                                'text-red-500':
                                                    type.estado == 0,
                                            }"
                                            class="h-5 w-5 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                                v-if="type.estado == 1"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                                v-else
                                            />
                                        </svg>
                                        Estado:
                                        <span class="font-normal">{{
                                            type.estado == 1
                                                ? "ACTIVO"
                                                : "INACTIVO"
                                        }}</span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(type.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === type.id"
                                        class="bg-white flex justify-between shadow-lg rounded-lg absolute right-0 mt-1 w-[154px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editType(type)"
                                            class="block px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-400 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-white"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="changeStatus(type)"
                                            class="block px-4 py-2 text-sm"
                                            :class="
                                                type.estado === 1
                                                    ? 'bg-orange-500 hover:bg-orange-400'
                                                    : 'bg-green-500 hover:bg-green-400'
                                            "
                                        >
                                            <v-icon
                                                v-if="type.estado == 1"
                                                name="gi-cancel"
                                                class="text-white"
                                            />
                                            <v-icon
                                                v-else
                                                name="fa-check"
                                                class="text-white"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteType(type)"
                                            class="block px-4 py-2 text-sm text-white bg-red-500 hover:bg-red-400 rounded-r-lg"
                                        >
                                            <v-icon
                                                name="bi-trash"
                                                class="text-white"
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Pagination class="mt-2" :pagination="types" />
                    </div>
                    <Modal :show="showModal" maxWidth="xl">
                        <TypeForm
                            :typer="typeObj"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
