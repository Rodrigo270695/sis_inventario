<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import UserForm from "./UserForm.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    users: Object,
    pdvs: Array,
    roles: Array,
    texto: String,
});

const form = useForm({
    user: Object,
});

let userObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);


const toggleOptions = (userId) => {
    if (openMenuId.value === userId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = userId;
    }
};

const addUser = () => {
    userObj.value = null;
    showModal.value = true;
};

const editUser = (user) => {
    openMenuId.value = null;
    userObj.value = user;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    userObj.value = null;
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

const changeStatus = (user) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de del Usuario?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("user.change", user), {
                preserveScroll: true,
            });
        }
    });
};

const deleteUser = (user) => {
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
            form.delete(route("user.destroy", user), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("user.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("user.index"));
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
                            <h2 class="text-xl sm:text-2xl text-slate-700">Gestionar Usuarios</h2>
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
                                placeholder="Buscar Usuarios"
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
                                @click="addUser"
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
                                                Punto de venta
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
                                                DNI
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Correo
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
                                            v-for="user in users.data"
                                            :key="user.id"
                                            class="bg-sky-100 hover:bg-sky-200"
                                        >
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ user.pdv.zonal.nombre }}->{{ user.pdv.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ user.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ user.dni }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ user.email }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                <p
                                                    class="inline-block px-2 rounded-full h-auto justify-center items-center text-xs md:text-sm"
                                                    :class="{
                                                        ' bg-green-500 text-white':
                                                            user.estado == 1,
                                                        'bg-red-500 rounded text-white':
                                                            user.estado == 0,
                                                    }"
                                                >
                                                    {{
                                                        user.estado == 1
                                                            ? "ACTIVO"
                                                            : "INACTIVO"
                                                    }}
                                                </p>
                                            </td>
<!--                                             <td
                                                class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div class="flex items-center justify-center gap-x-1">
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-400 cursor-pointer"
                                                            @click="editZonal(zonal)"
                                                        >
                                                            <v-icon
                                                                name="md-modeedit-round"
                                                            />
                                                            <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                                Editar Zonal
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="text-white p-1 rounded-md"
                                                            :class="{
                                                                'bg-orange-500 hover:bg-orange-400':
                                                                    zonal.estado ==
                                                                    1,
                                                                'bg-green-500 hover:bg-green-400':
                                                                    zonal.estado ==
                                                                    0,
                                                            }"
                                                            @click="
                                                                changeStatus(zonal)
                                                            "
                                                        >
                                                            <v-icon
                                                                v-if="
                                                                    zonal.estado ==
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
                                                                deleteZonal(zonal)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-trash"
                                                            />
                                                        </button>
                                                        <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                            Eliminar zonal
                                                        </span>
                                                    </div>
                                                </div>
                                            </td> -->
                                        </tr>
                                        <tr v-if="users.data.length <= 0">
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
                        <!-- Tarjetas -->
                        <Pagination class="mt-2" :pagination="users" />
                    </div>
                    <Modal :show="showModal">
                        <UserForm
                            :user="userObj"
                            :pdvs="props.pdvs"
                            :roles="props.roles"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
