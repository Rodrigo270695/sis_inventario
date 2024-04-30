<script setup>
import { defineProps } from "vue";
import { onMounted, nextTick } from "vue";
import JsBarcode from "jsbarcode";
import { jsPDF } from "jspdf";

const props = defineProps({
    pdvs: Array,
});

const teamStatusBackground = (status) => {
    switch (status) {
        case "BAJA":
            return "bg-red-100";
        case "DESECHADO":
            return "bg-violet-100";
        case "MANTENIMIENTO-DAÑO":
            return "bg-yellow-100";
        case "MANTENIMIENTO-GARANTIA":
            return "bg-ambar-100";
        case "RESERVADO":
            return "bg-purple-100";
        case "USO":
            return "bg-green-100";
        default:
            return "bg-white";
    }
};

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

onMounted(() => {
    if (Array.isArray(props.pdvs.data)) {
        props.pdvs.data.forEach((pdv) => {
            pdv.stores.forEach((store) => {
                store.teams.forEach((team) => {
                    generateBarcode(`barcode-${team.id}`, team.codigo_barras);
                });
            });
        });
    } else {
        console.error("Error: 'pdvs' no es un array o no está definido.");
    }
});

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
</script>

<template>
    <div class="pt-2">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <div
                v-for="pdv in pdvs.data"
                :key="pdv.id"
                class="bg-3D-50 rounded-2xl shadow-abajo-1 mb-8 p-5 sizing"
            >
                <h2
                    class="text-xl sm:text-2xl font-extrabold text-gray-600 mb-4 border-slate-300 uppercase"
                >
                    PDV: {{ pdv.nombre }}
                </h2>
                <div
                    v-for="store in pdv.stores"
                    :key="store.id"
                    class="bg-3D-50 rounded-lg m-3 p-4 shadow-abajo-2"
                >
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-600">
                        Tienda: {{ store.nombre }}
                    </h3>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 p-3"
                    >
                        <div
                            v-for="team in store.teams"
                            :key="team.id"
                            class="relative rounded-lg p-3 shadow-abajo-2"
                            :class="teamStatusBackground(team.estado)"
                        >
                            <h4
                                class="text-base sm:text-lg font-medium text-gray-700"
                            >
                                {{ team.make.equipmenttype.nombre }}->{{
                                    team.make.nombre
                                }}->{{ team.nombre }}
                            </h4>
                            <div class="flex justify-center gap-2 mt-2">
                                <div class="relative group">
                                    <button
                                        class="bg-yellow-100 hover:bg-yellow-200 text-white font-bold py-1 px-2 rounded-full shadow-abajo-1 focus:ring-1 focus:ring-sky-600"
                                        @click="$emit('edit-team', team)"
                                    >
                                        <v-icon
                                            class="text-gray-600"
                                            name="md-modeedit-round"
                                        />
                                    </button>
                                    <span
                                        class="absolute bottom-full mb-2 hidden group-hover:block w-[90px] p-2 text-xs text-white bg-sky-950 rounded-md z-50 -translate-x-1/2 left-1/2"
                                    >
                                        Editar Equipo
                                    </span>
                                </div>
                                <div class="relative group">
                                    <button
                                        class="bg-green-100 hover:bg-green-200 text-white font-bold py-1 px-2 rounded-full shadow-abajo-1 focus:ring-1 focus:ring-sky-600"
                                        @click="$emit('add-doc', team)"
                                    >
                                        <v-icon
                                            class="text-gray-600"
                                            name="md-fileupload-round"
                                        />
                                    </button>
                                    <span
                                        class="absolute bottom-full mb-2 hidden group-hover:block w-[90px] p-2 text-xs text-white bg-sky-950 rounded-md z-50 -translate-x-1/2 left-1/2"
                                    >
                                        Cargar Documento
                                    </span>
                                </div>
                                <div class="relative group">
                                    <button
                                        class="bg-green-100 hover:bg-green-200 text-white font-bold py-1 px-2 rounded-full shadow-abajo-1 focus:ring-1 focus:ring-sky-600"
                                        @click="
                                            downloadPdf(
                                                `barcode-${team.id}`,
                                                `${pdv.nombre}/${store.nombre}/${team.nombre}`
                                            )
                                        "
                                    >
                                        <v-icon
                                            class="text-gray-600"
                                            name="hi-solid-download"
                                        />
                                    </button>
                                    <span
                                        class="absolute bottom-full mb-2 hidden group-hover:block w-44 h-16 p-2 text-xs text-white bg-sky-950 rounded-md z-50 -translate-x-1/2 left-1/2"
                                    >
                                        <canvas
                                            :id="'barcode-' + team.id"
                                            class="w-40 rounded-md"
                                        ></canvas>
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <div
                                    v-for="accessory in team.accessories"
                                    :key="accessory.id"
                                    class="bg-blue-50 rounded-lg p-2 shadow-abajo-2 flex items-center justify-center cursor-pointer hover:shadow-abajo-2-cambio"
                                >
                                    <h5
                                        class="text-xs sm:text-sm font-extrabold text-slate-600 cursor-pointer w-full h-5 flex items-center justify-center p-2"
                                        @click="
                                            $emit('view-accessory', accessory)
                                        "
                                    >
                                        {{ accessory.nombre }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scope>
.sizing {
    box-sizing: border-box;
}
</style>
