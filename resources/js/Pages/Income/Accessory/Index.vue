<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, nextTick } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import AccesoryForm from "./AccesoryForm.vue";
import UpdateDocument from "./UpdateDocument.vue";
import AssignAccessory from "./AssignAccessory.vue";
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

/* const changeStatus = (accesory) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de del Accesorio?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("accesory.change", accesory), {
                preserveScroll: true,
            });
        }
    });
}; */

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
                                Gestionar Accesorios
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
                                placeholder="Buscar Accesorios"
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
                                class="bg-sky-800 hover:bg-sky-700 p-2 text-white rounded-lg flex items-center"
                                @click="addAccesory"
                            >
                                <v-icon
                                    name="io-add-circle-sharp"
                                    scale="1.1"
                                />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 bg-white">
                        <div class="block">
                            <div class="overflow-x-auto rounded-lg">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-cyan-800">
                                        <tr class="">
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-left text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                PDV
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Garantía
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-2 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Asignación
                                            </th>
                                            <th
                                                scope="col"
                                                class="py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
                                            >
                                                Código de Barras
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-semibold text-white uppercase tracking-wider border-l"
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
                                            v-for="accesory in accessories.data"
                                            :key="accesory.id"
                                            class="bg-sky-100 hover:bg-sky-200"
                                        >
                                            <td
                                                class="text-xs md:text-sm pl-3 py-3 whitespace-nowrap"
                                            >
                                                {{
                                                    accesory.store.pdv.nombre
                                                }}/{{ accesory.store.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                {{ accesory.nombre }}
                                            </td>
                                            <td
                                                class="text-xs md:text-sm py-2 whitespace-nowrap text-center"
                                            >
                                                <div
                                                    :class="
                                                        daysUntilWarrantyEnds(
                                                            accesory.fecha_compra,
                                                            accesory.garantia_tienda
                                                        ) > 30
                                                            ? 'bg-green-300'
                                                            : 'bg-red-300'
                                                    "
                                                    class="rounded-md h-7 inline-flex items-center justify-center px-2"
                                                >
                                                    {{
                                                        formatDate(
                                                            accesory.fecha_compra
                                                        )
                                                    }}
                                                    ({{
                                                        daysUntilWarrantyEnds(
                                                            accesory.fecha_compra,
                                                            accesory.garantia_tienda
                                                        )
                                                    }}
                                                    días restantes)
                                                </div>
                                            </td>
                                            <td
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                <div
                                                    class="h-7 inline-flex items-center justify-center font-bold rounded-md px-2 text-white"
                                                    :class="
                                                        accesory.estado_asignado ===
                                                        'NO ASIGNADO'
                                                            ? 'bg-slate-600 '
                                                            : 'bg-violet-400'
                                                    "
                                                >
                                                    {{
                                                        accesory.estado_asignado
                                                    }}
                                                </div>
                                            </td>
                                            <td
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                <div
                                                    class="flex justify-around"
                                                >
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
                                            <td
                                                class="text-xs md:text-sm py-3 whitespace-nowrap text-center"
                                            >
                                                {{ accesory.estado }}
                                            </td>
                                            <td
                                                class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-1"
                                                >
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-yellow-500 text-white p-1 rounded-md hover:bg-yellow-400 cursor-pointer"
                                                            @click="
                                                                editAccesory(
                                                                    accesory
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
                                                                Editar Accesorio
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-slate-500 text-white p-1 rounded-md hover:bg-slate-400 cursor-pointer"
                                                            @click="
                                                                addDoc(accesory)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="md-fileupload-round"
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
                                                                Cargar Doc.
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div
                                                        v-if="
                                                            accesory.estado_asignado ===
                                                            'NO ASIGNADO'
                                                        "
                                                        class="relative group"
                                                    >
                                                        <button
                                                            class="bg-violet-500 text-white p-1 rounded-md hover:bg-violet-400 cursor-pointer"
                                                            @click="
                                                                assign(accesory)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-node-plus-fill"
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
                                                                Asignar Equipo
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-500 text-white p-1 rounded-md hover:bg-red-400 cursor-pointer"
                                                            @click="
                                                                deleteAccesory(
                                                                    accesory
                                                                )
                                                            "
                                                        >
                                                            <v-icon
                                                                name="md-delete"
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
                                                                Eliminar
                                                                Accesorio
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="accessories.data.length <= 0">
                                            <td
                                                class="text-center text-slate-800 text-md sm:text-lg font-semibold bg-slate-300"
                                                colspan="7"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <Pagination class="mt-2" :pagination="accessories" />
                    </div>
                    <Modal :show="showModal" maxWidth="3xl">
                        <AccesoryForm
                            :accesory="accesoryObj"
                            :makes="makes"
                            :stores="stores"
                            @close-modal="closeModal"
                        />
                    </Modal>
                    <Modal :show="showModalDoc" maxWidth="lg">
                        <UpdateDocument
                            :accessory="accesoryObj"
                            @close-modal="closeModal"
                        />
                    </Modal>
                    <Modal :show="showModalAssign" maxWidth="lg">
                        <AssignAccessory
                            :accessory="accesoryObj"
                            :teams="teams"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
