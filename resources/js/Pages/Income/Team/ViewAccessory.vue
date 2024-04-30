<script setup>
import { defineProps, onMounted, nextTick } from "vue";
import JsBarcode from "jsbarcode";
import { jsPDF } from "jspdf";

const props = defineProps({
    accessory: Object,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES");
};

const emit = defineEmits(["close-modal"]);

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
    if (props.accessory && props.accessory.codigo_barras) {
        generateBarcode(
            `barcode-${props.accessory.id}`,
            props.accessory.codigo_barras
        );
    } else {
        console.error("No se encontró el código de barras del accesorio.");
    }
});
</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div
            class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center"
        >
            Detalles del Accesorio
        </div>
        <button @click="emit('close-modal')">
            <v-icon
                class="text-white rounded-md bg-red-400"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>
    <div class="p-5 bg-white rounded-lg shadow-lg max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">Nombre:</h3>
                    <p class="text-gray-800">{{ accessory.nombre }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">Modelo:</h3>
                    <p class="text-gray-800">{{ accessory.modelo || "N/A" }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">
                        Capacidad:
                    </h3>
                    <p class="text-gray-800">
                        {{ accessory.capacidad || "N/A" }}
                    </p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">Serie:</h3>
                    <p class="text-gray-800">{{ accessory.serie || "N/A" }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded flex justify-center">
                    <canvas
                        class="w-44"
                        :id="'barcode-' + accessory.id"
                    ></canvas>
                </div>
            </div>
            <div class="space-y-4">
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">
                        Estado Asignado:
                    </h3>
                    <p class="text-gray-800">{{ accessory.estado_asignado }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">Estado:</h3>
                    <p class="text-gray-800">{{ accessory.estado }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">
                        Fecha de Compra:
                    </h3>
                    <p class="text-gray-800">
                        {{ formatDate(accessory.fecha_compra) }}
                    </p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">
                        Garantía de Tienda:
                    </h3>
                    <p class="text-gray-800">
                        {{ accessory.garantia_tienda }} meses
                    </p>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <h3 class="text-lg font-semibold text-gray-600">
                        Garantía de Marca:
                    </h3>
                    <p class="text-gray-800">
                        {{ accessory.garantia_marca || "N/A" }} meses
                    </p>
                </div>

            </div>
        </div>
        <div class="mt-6 bg-gray-100 p-4 rounded">
            <h3 class="text-lg font-semibold text-gray-600">Descripción:</h3>
            <p class="text-gray-800 whitespace-pre-line">
                {{ accessory.descripcion || "N/A" }}
            </p>
        </div>
    </div>
</template>
