<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import UserForm from "./UserForm.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
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

const closeModal = () => {
    showModal.value = false;
    userObj.value = null;
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

const changeStatus = (user) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado del Usuario?",
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

const search = () => {
    form.get(route("user.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("user.index"));
};
</script>

<template>
    <AppLayout title="Usuarios">
        <template #header>
            <IndexHeader title="Gestionar Usuarios" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addUser"
                        @update:query="query = $event"
                        placeholder="Buscar Usuarios"
                    />

                    <div class="p-3">
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-blue-200 shadow-abajo-2">
                                        <tr class="">
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Punto de venta
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Rol
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Correo
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Estado
                                            </th>
                                            <th
                                                scope="col"
                                                class="border-l"
                                            ></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="user in users.data"
                                            :key="user.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap"
                                            >
                                                {{ user.pdv.zonal.nombre }}->{{ user.pdv.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                            {{ user.dni }} - {{ user.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ user.roles[0].name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ user.email }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base px-2 py-3 whitespace-nowrap text-center"
                                                :class="
                                                    user.estado === 1
                                                        ? 'text-green-500'
                                                        : 'text-red-300'
                                                "
                                            >
                                                {{
                                                    user.estado === 1
                                                        ? "ACTIVO"
                                                        : "INACTIVO"
                                                }}
                                            </td>
                                            <td
                                                class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-3"
                                                >
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-yellow-200 text-slate-500 p-1 rounded-md hover:bg-yellow-300 cursor-pointer shadow-abajo-1"
                                                            @click="
                                                                editUser(user)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="md-modeedit-round"
                                                            />
                                                            <span
                                                                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="
                                                                    left: 50%;
                                                                    transform: translateX(
                                                                        -50%
                                                                    );
                                                                    transition: opacity
                                                                        0.3s;
                                                                "
                                                            >
                                                                Editar usuario
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="text-slate-500 p-1 rounded-md cursor-pointer shadow-abajo-1"
                                                            :class="{
                                                                'bg-orange-200 hover:bg-orange-300':
                                                                    user.estado ==
                                                                    1,
                                                                'bg-green-300 hover:bg-green-400':
                                                                    user.estado ==
                                                                    0,
                                                            }"
                                                            @click="
                                                                changeStatus(
                                                                    user
                                                                )
                                                            "
                                                        >
                                                            <v-icon
                                                                v-if="
                                                                    user.estado ==
                                                                    1
                                                                "
                                                                name="gi-cancel"
                                                            />
                                                            <v-icon
                                                                v-else
                                                                name="fa-check"
                                                            />
                                                            <span
                                                                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="
                                                                    left: 50%;
                                                                    transform: translateX(
                                                                        -50%
                                                                    );
                                                                    transition: opacity
                                                                        0.3s;
                                                                "
                                                            >
                                                                cambiar estado
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                deleteUser(user)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-trash"
                                                            />
                                                            <span
                                                                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="
                                                                    left: 50%;
                                                                    transform: translateX(
                                                                        -50%
                                                                    );
                                                                    transition: opacity
                                                                        0.3s;
                                                                "
                                                            >
                                                                Eliminar usuario
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="users.data.length <= 0">
                                            <td
                                                class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-50 shadow-abajo-2"
                                                colspan="9"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Versión mobile -->
                        <div class="block sm:hidden rounded-lg">
                            <div
                                v-for="user in users.data"
                                :key="user.id"
                                class="p-4 mx-1 mt-4 bg-blue-50 hover:bg-blue-100 rounded-lg relative shadow-abajo-1"
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
                                    <h3
                                        class="text-lg font-bold text-slate-700"
                                    >
                                        Nombre:
                                        <span class="font-normal">
                                            {{ user.name }}
                                        </span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>PDV:</strong>
                                        <span class="text-gray-600 ml-1">
                                            {{user.pdv.zonal.nombre}}/{{ user.pdv.nombre}}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Rol:</strong>
                                        <span class="text-gray-600 ml-1">
                                            {{user.roles[0].name}}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Correo:</strong>
                                        <span class="text-gray-600 ml-1">
                                            {{user.email}}
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Estado:</strong>
                                        <span
                                            class="ml-1"
                                            :class="
                                                user.estado === 1
                                                    ? 'text-green-500'
                                                    : 'text-red-300'
                                            "
                                        >
                                            {{
                                                user.estado === 1
                                                    ? "ACTIVO"
                                                    : "INACTIVO"
                                            }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(user.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === user.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[130px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="editUser(user)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-slate-500"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="changeStatus(user)"
                                            class="block px-3 py-1 text-sm text-slate-500"
                                            :class="{
                                                'bg-orange-200 hover:bg-orange-300':
                                                    user.estado == 1,
                                                'bg-green-200 hover:bg-green-300':
                                                    user.estado == 0,
                                            }"
                                        >
                                            <v-icon
                                                v-if="user.estado == 1"
                                                name="gi-cancel"
                                            />
                                            <v-icon v-else name="fa-check" />
                                        </a>
                                        <a
                                            href="#"
                                            @click="deleteUser(user)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-red-300 hover:bg-red-400 rounded-r-lg"
                                        >
                                            <v-icon
                                                name="bi-trash"
                                                class="text-slate-500"
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
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
