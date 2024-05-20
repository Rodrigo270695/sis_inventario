<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import SolicitudeForm from "./SolicitudeForm.vue";
import ViewSolicitude from "./ViewSolicitude.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    solicitudes: Object,
    types: Array,
    texto: String,
});

const form = useForm({
    solicitude: Object,
});

let solicitudeObj = ref(null);
let showModal = ref(false);
let viewModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (solicitudeId) => {
    if (openMenuId.value === solicitudeId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = solicitudeId;
    }
};

const addSolicitude = () => {
    solicitudeObj.value = null;
    showModal.value = true;
};

const viewSolicitude = (solicitude) => {
    solicitudeObj.value = solicitude;
    viewModal.value = true;
};

const editSolicitude = (solicitude) => {
    openMenuId.value = null;
    solicitudeObj.value = solicitude;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    viewModal.value = false;
    solicitudeObj.value = null;
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

const deleteSolicitude = (solicitude) => {
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
            form.delete(route("solicitude.destroy", solicitude), {
                preserveScroll: true,
            });
        }
    });
};

const changeStatusSolicitude = (solicitude) => {
    Swal.fire({
        title: "¿Deseas cambiar el estado de la solicitud?",
        icon: "question",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Aprobar",
        denyButtonText: "Rechazar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("solicitude.approve", solicitude), {
                preserveScroll: true,
            });
        } else if (result.isDenied) {
            form.put(route("solicitude.reject", solicitude), {
                preserveScroll: true,
            });
        }
    });
};

const changeStatusManagement = (solicitude) => {
    Swal.fire({
        title: "¿Deseas cambiar el estado de la solicitud para la gerencia?",
        icon: "question",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Aprobar",
        denyButtonText: "Rechazar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("solicitude.approveManagement", solicitude), {
                preserveScroll: true,
            });
        } else if (result.isDenied) {
            form.put(route("solicitude.rejectManagement", solicitude), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("solicitude.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("solicitude.index"));
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="pt-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div
                        class="flex justify-between font-extrabold border-b px-4 py-2"
                        title="Refrescar la página"
                    >
                        <div class="h-11 inline-flex items-center w-full">
                            <h2 class="text-xl sm:text-2xl text-slate-700">
                                Gestionar Solicitudes
                            </h2>
                        </div>
                        <button
                            class="bg-green-600 hover:bg-green-500 w-12 rounded-md"
                            @click="goToIndex"
                        >
                            <v-icon
                                class="text-white"
                                name="io-reload-circle-sharp"
                                scale="1.7"
                            />
                        </button>
                    </div>

                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-64 md:w-72 lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                placeholder="Buscar solicitud"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="search"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-800 rounded-e-md hover:bg-sky-700"
                            >
                                <v-icon name="fa-search" scale="1.5" />
                            </button>
                        </div>
                        <div>
                            <button
                                v-if="
                                    $page.props.user.roles.includes(
                                        'Supervisor 1'
                                    ) |
                                        $page.props.user.roles.includes(
                                            'Supervisor 2'
                                        )
                                "
                                class="bg-sky-800 hover:bg-sky-700 p-2 text-white rounded-lg flex items-center"
                                @click="addSolicitude"
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
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-cyan-800">
                                        <tr class="">
                                            <th
                                                scope="col"
                                                class="py-2 pl-2 text-left text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                ID
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Fecha
                                            </th>
                                            <th
                                                v-if="
                                                    $page.props.user.roles.includes(
                                                        'Administrador'
                                                    ) |
                                                        $page.props.user.roles.includes(
                                                            'Aprobador'
                                                        )
                                                "
                                                scope="col"
                                                class="py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                PDV
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Tipo
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
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
                                            v-for="solicitude in solicitudes.data"
                                            :key="solicitude.id"
                                            class="bg-sky-100 hover:bg-sky-200"
                                        >
                                            <td
                                                class="text-xs md:text-sm py-3 pl-3 whitespace-nowrap"
                                            >
                                                {{ solicitude.id }}
                                            </td>
                                            <td
                                                v-if="
                                                    $page.props.user.roles.includes(
                                                        'Supervisor 1'
                                                    ) |
                                                        $page.props.user.roles.includes(
                                                            'Supervisor 2'
                                                        )
                                                "
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                {{
                                                    new Date(
                                                        solicitude.created_at
                                                    ).toLocaleDateString(
                                                        "es-PE",
                                                        {
                                                            day: "2-digit",
                                                            month: "2-digit",
                                                            year: "numeric",
                                                        }
                                                    )
                                                }}
                                            </td>
                                            <td
                                                v-else
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                {{
                                                    new Date(
                                                        solicitude.updated_at
                                                    ).toLocaleDateString(
                                                        "es-PE",
                                                        {
                                                            day: "2-digit",
                                                            month: "2-digit",
                                                            year: "numeric",
                                                        }
                                                    )
                                                }}
                                            </td>
                                            <td
                                                v-if="
                                                    $page.props.user.roles.includes(
                                                        'Administrador'
                                                    ) |
                                                        $page.props.user.roles.includes(
                                                            'Aprobador'
                                                        )
                                                "
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                {{ solicitude.pdv.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                {{
                                                    solicitude.typerequest
                                                        .nombre
                                                }}
                                            </td>
                                            <td
                                                v-if="
                                                    $page.props.user.roles.includes(
                                                        'Supervisor 2'
                                                    )
                                                "
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center font-extrabold uppercase"
                                                :class="
                                                    solicitude.aprobacion_local ===
                                                    1
                                                        ? 'bg-green-300 hover:bg-green-400'
                                                        : solicitude.aprobacion_local ===
                                                          0
                                                        ? 'bg-orange-300 hover:bg-orange-400'
                                                        : 'bg-red-300 hover:bg-red-400'
                                                "
                                            >
                                                {{
                                                    solicitude.aprobacion_local ===
                                                    1
                                                        ? "Aprobado"
                                                        : solicitude.aprobacion_local ===
                                                          0
                                                        ? "Pendiente"
                                                        : "Rechazado"
                                                }}
                                            </td>
                                            <td
                                                v-else-if="
                                                    $page.props.user.roles.includes(
                                                        'Supervisor 1'
                                                    )
                                                "
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center font-extrabold uppercase"
                                                :class="
                                                    solicitude.aprobacion_gerencia ===
                                                    1
                                                        ? 'bg-green-300 hover:bg-green-400'
                                                        : solicitude.aprobacion_gerencia ===
                                                          0
                                                        ? 'bg-orange-300 hover:bg-orange-400'
                                                        : 'bg-red-300 hover:bg-red-400'
                                                "
                                            >
                                                {{
                                                    solicitude.aprobacion_gerencia ===
                                                    1
                                                        ? "Aprobado Gerencia"
                                                        : solicitude.aprobacion_gerencia ===
                                                          0
                                                        ? "Pendiente Gerencia"
                                                        : "Rechazado Gerencia"
                                                }}
                                            </td>
                                            <td
                                                v-else
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center font-extrabold uppercase"
                                                :class="
                                                    solicitude.aprobacion_gerencia ===
                                                    1
                                                        ? 'bg-green-300 hover:bg-green-400'
                                                        : solicitude.aprobacion_gerencia ===
                                                          0
                                                        ? 'bg-orange-300 hover:bg-orange-400'
                                                        : 'bg-red-300 hover:bg-red-400'
                                                "
                                            >
                                                {{
                                                    solicitude.aprobacion_gerencia ===
                                                    1
                                                        ? "Aprobado"
                                                        : solicitude.aprobacion_gerencia ===
                                                          0
                                                        ? "Pendiente"
                                                        : "Rechazado"
                                                }}
                                            </td>

                                            <td
                                                class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-1"
                                                >
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-blue-500 text-white p-1 rounded-md hover:bg-blue-400 cursor-pointer"
                                                            @click="
                                                                viewSolicitude(
                                                                    solicitude
                                                                )
                                                            "
                                                        >
                                                            <v-icon
                                                                name="fa-eye"
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
                                                                Ver solicitud
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            solicitude.aprobacion_local ===
                                                            0
                                                        "
                                                        class="relative group"
                                                    >
                                                        <button
                                                            class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-400 cursor-pointer"
                                                            @click="
                                                                editSolicitude(
                                                                    solicitude
                                                                )
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
                                                                Editar solic.
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            solicitude.aprobacion_local ===
                                                            1
                                                        "
                                                        class="relative group"
                                                    >
                                                        <button
                                                            class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-400 cursor-pointer"
                                                            @click="
                                                                editSolicitude(
                                                                    solicitude
                                                                )
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
                                                                Editar solic.
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            $page.props.user.roles.includes(
                                                                'Supervisor 1'
                                                            ) &
                                                            (solicitude.aprobacion_local ===
                                                                0)
                                                        "
                                                        class="relative group"
                                                    >
                                                        <button
                                                            class="bg-slate-500 text-white p-1 rounded-md hover:bg-slate-400 cursor-pointer"
                                                            @click="
                                                                changeStatusSolicitude(
                                                                    solicitude
                                                                )
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-arrow-left-right"
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
                                                                Cambiar Estado
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            ($page.props.user.roles.includes(
                                                                'Aprobador'
                                                            ) |
                                                                $page.props.user.roles.includes(
                                                                    'Administrador'
                                                                )) &
                                                            (solicitude.aprobacion_gerencia ===
                                                                0)
                                                        "
                                                        class="relative group"
                                                    >
                                                        <button
                                                            class="bg-slate-500 text-white p-1 rounded-md hover:bg-slate-400 cursor-pointer"
                                                            @click="
                                                                changeStatusManagement(
                                                                    solicitude
                                                                )
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-arrow-left-right"
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
                                                                Cambiar Estado
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="solicitudes.data.length <= 0">
                                            <td
                                                class="text-center text-slate-800 text-md sm:text-lg font-semibold bg-slate-300"
                                                colspan="6"
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
                                v-for="solicitude in solicitudes.data"
                                :key="solicitude.id"
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
                                        PDV:
                                        <span class="font-normal">{{
                                            solicitude.pdv.nombre
                                        }}</span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md">
                                    <p>
                                        <strong>Tipo:</strong>
                                        <span class="text-gray-700 ml-1">{{
                                            solicitude.typerequest.nombre
                                        }}</span>
                                    </p>

                                    <p
                                        v-if="
                                            $page.props.user.roles.includes(
                                                'Supervisor 2'
                                            )
                                        "
                                        :class="
                                            solicitude.aprobacion_local === 1
                                                ? 'bg-green-300 hover:bg-green-400'
                                                : solicitude.aprobacion_local ===
                                                  0
                                                ? 'bg-orange-300 hover:bg-orange-400'
                                                : 'bg-red-300 hover:bg-red-400'
                                        "
                                        class="flex items-center rounded-md p-1"
                                    >
                                        <strong>Estado:</strong>
                                        <span class="text-gray-700 ml-1">
                                            {{
                                                solicitude.aprobacion_local ===
                                                1
                                                    ? "Aprobado"
                                                    : solicitude.aprobacion_local ===
                                                      0
                                                    ? "Pendiente"
                                                    : "Rechazado"
                                            }}
                                        </span>
                                    </p>
                                    <p
                                        v-else-if="
                                            $page.props.user.roles.includes(
                                                'Supervisor 1'
                                            )
                                        "
                                        :class="
                                            solicitude.aprobacion_gerencia === 1
                                                ? 'bg-green-300 hover:bg-green-400'
                                                : solicitude.aprobacion_gerencia ===
                                                  0
                                                ? 'bg-orange-300 hover:bg-orange-400'
                                                : 'bg-red-300 hover:bg-red-400'
                                        "
                                        class="flex items-center rounded-md p-1"
                                    >
                                        <strong>Estado:</strong>
                                        <span class="text-gray-700 ml-1">
                                            {{
                                                solicitude.aprobacion_gerencia ===
                                                1
                                                    ? "Aprobado Gerencia"
                                                    : solicitude.aprobacion_gerencia ===
                                                      0
                                                    ? "Pendiente Gerencia"
                                                    : "Rechazado Gerencia"
                                            }}
                                        </span>
                                    </p>
                                    <p
                                        v-else
                                        :class="
                                            solicitude.aprobacion_gerencia === 1
                                                ? 'bg-green-300 hover:bg-green-400'
                                                : solicitude.aprobacion_gerencia ===
                                                  0
                                                ? 'bg-orange-300 hover:bg-orange-400'
                                                : 'bg-red-300 hover:bg-red-400'
                                        "
                                        class="flex items-center rounded-md p-1"
                                    >
                                        <strong>Estado:</strong>
                                        <span class="text-gray-700 ml-1">
                                            {{
                                                solicitude.aprobacion_gerencia ===
                                                1
                                                    ? "Aprobado"
                                                    : solicitude.aprobacion_gerencia ===
                                                      0
                                                    ? "Pendiente"
                                                    : "Rechazado"
                                            }}
                                        </span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button
                                        @click="toggleOptions(solicitude.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100"
                                    >
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div
                                        v-if="openMenuId === solicitude.id"
                                        class="bg-white flex justify-between shadow-lg rounded-lg absolute right-0 mt-1 w-[154px] z-20 text-center"
                                    >
                                        <a
                                            href="#"
                                            @click="viewSolicitude(solicitude)"
                                            class="block px-4 py-2 text-sm text-white bg-blue-500 hover:bg-blue-400 rounded-l-lg"
                                        >
                                            <v-icon
                                                name="fa-eye"
                                                class="text-white"
                                            />
                                        </a>
                                        <a
                                            href="#"
                                            @click="editSolicitude(solicitude)"
                                            class="block px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-400"
                                        >
                                            <v-icon
                                                name="md-modeedit-round"
                                                class="text-white"
                                            />
                                        </a>
                                        <a
                                            v-if="
                                                $page.props.user.roles.includes(
                                                    'Supervisor 1'
                                                ) &
                                                (solicitude.aprobacion_local ===
                                                    0)
                                            "
                                            href="#"
                                            @click="
                                                changeStatusManagement(
                                                    solicitude
                                                )
                                            "
                                            class="block px-4 py-2 text-sm text-white bg-slate-500 hover:bg-slate-400 rounded-r-lg"
                                        >
                                            <v-icon
                                                name="bi-arrow-left-right"
                                                class="text-white"
                                            />
                                        </a>
                                        <a
                                            v-if="
                                                ($page.props.user.roles.includes(
                                                    'Aprobador'
                                                ) |
                                                    $page.props.user.roles.includes(
                                                        'Administrador'
                                                    )) &
                                                (solicitude.aprobacion_gerencia ===
                                                    0)
                                            "
                                            href="#"
                                            @click="
                                                changeStatusManagement(
                                                    solicitude
                                                )
                                            "
                                            class="block px-4 py-2 text-sm text-white bg-slate-500 hover:bg-slate-400 rounded-r-lg"
                                        >
                                            <v-icon
                                                name="bi-arrow-left-right"
                                                class="text-white"
                                            />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Pagination class="mt-2" :pagination="solicitudes" />
                    </div>
                    <Modal :show="showModal" maxWidth="xl">
                        <SolicitudeForm
                            :solicitude="solicitudeObj"
                            :types="types"
                            @close-modal="closeModal"
                        />
                    </Modal>
                    <Modal :show="viewModal" maxWidth="2xl">
                        <ViewSolicitude
                            :solicitude="solicitudeObj"
                            :types="types"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
