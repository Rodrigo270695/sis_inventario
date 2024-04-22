<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import StoreForm from "./StoreForm.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    stores: Array,
    pdvs: Array,
    texto: String,
});

const form = useForm({
    store: Object,
});

let storeObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);


const toggleOptions = (storeId) => {
    if (openMenuId.value === storeId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = storeId;
    }
};

const addStore = () => {
    storeObj.value = null;
    showModal.value = true;
};

const editStore = (store) => {
    openMenuId.value = null;
    storeObj.value = store;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    storeObj.value = null;
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

const changeStatus = (store) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado del Alamcén?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("store.change", store), {
                preserveScroll: true,
            });
        }
    });
};

const deleteStore = (store) => {
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
            form.delete(route("store.destroy", store), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("store.search", { texto: query.value }));
};

const goToIndex = () => {
    window.location.href = route("store.index");
};

</script>

<template>
    <AppLayout title="Dashboard">
        <div class="pt-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div
                        class="text-xl sm:text-2xl px-4 h-11 inline-flex items-center w-full text-slate-700 font-extrabold border-b"
                    >
                        <h2>Gestionar Almacen</h2>
                    </div>

                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-64 md:w-72 lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                placeholder="Buscar Almacén"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="goToIndex"
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
                                @click="addStore"
                            >
                                <v-icon
                                    name="io-add-circle-sharp"
                                    scale="1.3"
                                />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="hidden sm:block">
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
                                                Pdv
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                estado
                                            </th>
                                            <th class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr
                                            v-for="store in stores.data"
                                            :key="store.id"
                                            class="bg-sky-100 hover:bg-sky-200"
                                        >
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ store.pdv.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ store.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                <p
                                                    class="inline-block px-2 rounded-full h-auto justify-center items-center text-xs md:text-sm"
                                                    :class="{
                                                        ' bg-green-500 text-white':
                                                            store.estado == 1,
                                                        'bg-red-500 rounded text-white':
                                                            store.estado == 0,
                                                    }"
                                                >
                                                    {{
                                                        store.estado == 1
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
                                                            @click="
                                                                editStore(store)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="md-modeedit-round"
                                                            />
                                                        </button>
                                                        <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                            Editar pdv
                                                        </span>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="text-white p-1 rounded-md"
                                                            :class="{
                                                                'bg-orange-500 hover:bg-orange-400':
                                                                    store.estado ==
                                                                    1,
                                                                'bg-green-500 hover:bg-green-400':
                                                                    store.estado ==
                                                                    0,
                                                            }"
                                                            @click="
                                                                changeStatus(store)
                                                            "
                                                        >
                                                            <v-icon
                                                                v-if="
                                                                    store.estado ==
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
                                                                deleteStore(store)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-trash"
                                                            />
                                                        </button>
                                                        <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                            Eliminar pdv
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="stores.data.length <= 0">
                                            <td
                                                class="text-center text-slate-800 text-md sm:text-lg font-semibold bg-slate-300"
                                                colspan="5"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="block sm:hidden">
                            <div
                                v-for="store in stores.data"
                                :key="store.id"
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
                                        Pdv:
                                        <span class="font-normal">
                                            {{store.pdv.nombre}}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md">
                                    <p>
                                        <strong>Nombre:</strong>
                                        <span class="text-gray-700 ml-1">
                                            {{store.nombre}}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Dirección:</strong>
                                        <span class="text-gray-700 ml-1">
                                            {{store.direccion}}
                                        </span>
                                    </p>
                                    <p
                                        :class="{
                                            'text-green-500': store.estado == 1,
                                            'text-red-500': store.estado == 0,
                                        }"
                                        class="flex items-center"
                                    >
                                        <svg
                                            :class="{
                                                'text-green-500':
                                                    store.estado == 1,
                                                'text-red-500':
                                                    store.estado == 0,
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
                                                v-if="store.estado == 1"
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
                                            store.estado == 1
                                                ? "ACTIVO"
                                                : "INACTIVO"
                                        }}</span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(store.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === store.id"
                                        class="bg-white flex justify-between shadow-lg rounded-lg absolute right-0 mt-1 w-[154px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editStore(store)"
                                            class="block px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-400 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-white"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="changeStatus(store)"
                                            class="block px-4 py-2 text-sm"
                                            :class="
                                                store.estado === 1
                                                    ? 'bg-orange-500 hover:bg-orange-400'
                                                    : 'bg-green-500 hover:bg-green-400'
                                            "
                                        >
                                            <v-icon
                                                v-if="store.estado == 1"
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
                                            @click="deleteStore(store)"
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
                        <Pagination class="mt-2" :pagination="stores" />
                    </div>
                    <Modal :show="showModal">
                        <StoreForm
                            :store="storeObj"
                            :pdvs="pdvs"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
