<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, nextTick } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import Modal from "@/Components/Modal.vue";
import AccesoryForm from "./AccesoryForm.vue";
import UpdateDocument from "./UpdateDocument.vue";
import AssignAccessory from "./AssignAccessory.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import JsBarcode from "jsbarcode";
import { jsPDF } from "jspdf";
import { parseISO, format, addMonths } from "date-fns";

const props = defineProps({
    accessories: Object,
    makes: Array,
    stores: Array,
    teams: Array,
    texto: String,
});

const form = useForm({
    accesory: Object,
});

let accesoryObj = ref(null);
let showModal = ref(false);
let showModalDoc = ref(false);
let showModalAssign = ref(false);
let query = ref(props.texto);

const addDoc = (accesory) => {
    showModalDoc.value = true;
    accesoryObj.value = accesory;
};

const assign = (accesory) => {
    showModalAssign.value = true;
    accesoryObj.value = accesory;
};

const addAccesory = () => {
    accesoryObj.value = null;
    showModal.value = true;
};

const editAccesory = (accesory) => {
    accesoryObj.value = accesory;
    showModal.value = true;
};

const closeModal = () => {
    showModalDoc.value = false;
    showModalAssign.value = false;
    showModal.value = false;
    accesoryObj.value = null;
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

const deleteAccesory = (accesory) => {
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
            form.delete(route("accessory.destroy", accesory), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("accessory.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("accessory.index"));
};

/* Codigo de barras */
const generateBarcode = (elementId, codigo) => {
    nextTick(() => {
        JsBarcode(`#${elementId}`, codigo, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 30,
            displayValue: true,
        });
    });
};

const downloadPdf = (elementId, nombreArchivo) => {
    const canvas = document.getElementById(elementId);
    if (canvas) {
        const imgData = canvas.toDataURL("image/jpeg");
        const doc = new jsPDF();
        doc.text(nombreArchivo, 8, 17);
        doc.addImage(imgData, "JPEG", 10, 20);
        doc.save(`${nombreArchivo}.pdf`);
    } else {
        console.error(
            "No se pudo encontrar el elemento canvas para generar el PDF"
        );
    }
};

onMounted(() => {
    if (props.accessories && props.accessories.data) {
        props.accessories.data.forEach((accesory) => {
            generateBarcode(`barcode-${accesory.id}`, accesory.codigo_barras);
        });
    } else {
        console.error(
            "No se encontraron accesorios o la estructura de datos no es la esperada."
        );
    }
});

/* Calcular garantia */
const formatDate = (dateString) => {
    const date = parseISO(dateString);
    return format(date, "dd/MM/yy"); // Formatea la fecha en el formato dd/mm/YYYY
};

// Función para calcular la fecha de finalización de la garantía
const calculateWarrantyEnd = (purchaseDate, warrantyMonths) => {
    const date = parseISO(purchaseDate);
    const warrantyEndDate = addMonths(date, warrantyMonths);
    return format(warrantyEndDate, "dd/MM/yyyy");
};

// Función para calcular los días restantes hasta el final de la garantía
const daysUntilWarrantyEnds = (purchaseDate, warrantyMonths) => {
    const today = new Date();
    const warrantyEndDate = addMonths(parseISO(purchaseDate), warrantyMonths);
    const timeDiff = warrantyEndDate.getTime() - today.getTime();
    const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
    return daysDiff > 0 ? daysDiff : 0;
};
</script>

<template>
    <AppLayout title="Accesorios">
        <template #header>
            <IndexHeader title="Gestionar Accesorios" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addAccesory"
                        @update:query="query = $event"
                        placeholder="Buscar Accesorios"
                    />
                    <div class="p-3">
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-blue-200 shadow-abajo-2">
                                        <tr>
                                            <th scope="col" class="px-2 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                PDV
                                            </th>
                                            <th scope="col" class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Nombre
                                            </th>
                                            <th scope="col" class="py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Garantía
                                            </th>
                                            <th scope="col" class="px-2 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Asignación
                                            </th>
                                            <th scope="col" class="py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Código de Barras
                                            </th>
                                            <th scope="col" class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Estado
                                            </th>
                                            <th scope="col" class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr v-for="accesory in accessories.data" :key="accesory.id" class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                                            <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap">
                                                {{ accesory.store.pdv.nombre }}/{{ accesory.store.nombre }}
                                            </td>
                                            <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                                {{ accesory.nombre }}
                                            </td>
                                            <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                                <div :class="daysUntilWarrantyEnds(accesory.fecha_compra, accesory.garantia_tienda) > 30 ? 'bg-green-300' : 'bg-red-300'" class="rounded-md h-7 inline-flex items-center justify-center px-2">
                                                    {{ formatDate(accesory.fecha_compra) }} ({{ daysUntilWarrantyEnds(accesory.fecha_compra, accesory.garantia_tienda) }} días restantes)
                                                </div>
                                            </td>
                                            <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                                <div class="h-7 inline-flex items-center justify-center font-bold rounded-md px-2 text-white" :class="accesory.estado_asignado === 'NO ASIGNADO' ? 'bg-slate-600 ' : 'bg-violet-400'">
                                                    {{ accesory.estado_asignado }}
                                                </div>
                                            </td>
                                            <td class="text-xs md:text-base text-slate-500 px-2 py-3 whitespace-nowrap text-center">
                                                <div class="flex justify-around">
                                                    <canvas
                                                        class="w-36 cursor-pointer rounded-md"
                                                        :id="
                                                            'barcode-' +
                                                            accesory.id
                                                        "
                                                        @click="
                                                            downloadPdf(
                                                                'barcode-' +
                                                                    accesory.id,
                                                                `${accesory.store.pdv.nombre}/${accesory.store.nombre}/${accesory.nombre}`
                                                            )
                                                        "
                                                    >
                                                    </canvas>
                                                </div>
                                            </td>
                                            <td class="text-xs md:text-base px-2 py-3 whitespace-nowrap text-center text-slate-500">
                                                {{ accesory.estado  }}
                                            </td>
                                            <td class="px-2 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center gap-x-1">
                                                    <div class="relative group">
                                                        <button class="bg-yellow-200 text-slate-500 p-1 rounded-md hover:bg-yellow-300 cursor-pointer shadow-abajo-1" @click="editAccesory(accesory)">
                                                            <v-icon name="md-modeedit-round" />
                                                            <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md" style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                                Editar Accesorio
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button class="bg-slate-300 text-slate-500 p-1 rounded-md hover:bg-slate-400 cursor-pointer shadow-abajo-1" @click="addDoc(accesory)">
                                                            <v-icon name="md-fileupload-round" />
                                                            <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md" style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                                Cargar Doc.
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div v-if="accesory.estado_asignado === 'NO ASIGNADO'" class="relative group">
                                                        <button class="bg-violet-300 text-slate-500 p-1 rounded-md hover:bg-violet-400 cursor-pointer shadow-abajo-1" @click="assign(accesory)">
                                                            <v-icon name="bi-node-plus-fill" />
                                                            <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md" style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                                Asignar Equipo
                                                            </span> 
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 cursor-pointer shadow-abajo-1" @click="deleteAccesory(accesory)">
                                                            <v-icon name="md-delete" />
                                                            <span class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md" style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                                Eliminar Accesorio
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="accessories.data.length <= 0">
                                            <td class="text-center text-slate-800 text-md sm:text-lg font-semibold bg-slate-200" colspan="7">
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Versión mobile -->
                        <div class="block sm:hidden rounded-lg">
                            <div v-for="accesory in accessories.data" :key="accesory.id" class="p-4 mx-1 mt-4 bg-blue-50 hover:bg-blue-100 rounded-lg relative shadow-abajo-1">
                                <!-- Contenido de la tarjeta -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-slate-700">
                                        Accesorio:
                                        <span class="font-normal">{{ accesory.nombre }}</span>
                                    </h3>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-md text-slate-700">
                                        Estado:
                                        <span class="font-normal">{{ accesory.estado == 1 ? 'Activo' : 'Inactivo' }}</span>
                                    </div>
                                    <div class="absolute top-0 right-0 p-2 z-10">
                                        <div class="flex items-center space-x-1">
                                            <a href="#" @click="editAccesory(accesory)" class="block px-3 py-1 text-sm text-slate-500 bg-yellow-300 hover:bg-yellow-400 rounded-l-lg">
                                                <v-icon name="md-modeedit-round" class="text-slate-500" />
                                            </a>
                                            <a href="#" @click="deleteAccesory(accesory)" class="block px-3 py-1 text-sm text-slate-500 bg-red-300 hover:bg-red-400 rounded-r-lg">
                                                <v-icon name="bi-trash" class="text-slate-500" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Pagination class="mt-2" :pagination="accessories" />
                    </div>
                    <Modal :show="showModal" maxWidth="3xl">
                        <AccesoryForm :accesory="accesoryObj" :makes="makes" :stores="stores" @close-modal="closeModal" />
                    </Modal>
                    <Modal :show="showModalDoc" maxWidth="lg">
                        <UpdateDocument :accessory="accesoryObj" @close-modal="closeModal" />
                    </Modal>
                    <Modal :show="showModalAssign" maxWidth="lg">
                        <AssignAccessory :accessory="accesoryObj" :teams="teams" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
