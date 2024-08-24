<script setup>
import { defineProps, computed, ref, onMounted, nextTick } from "vue";
import JsBarcode from "jsbarcode";
import { jsPDF } from "jspdf";

const props = defineProps({
    pdvs: Object,
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
    const element = document.getElementById(elementId);
    if (element) {
        JsBarcode(`#${elementId}`, codigo, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 30,
            displayValue: true,
        });
    }
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

const groupedByPdv = computed(() => {
    return props.pdvs.data.map((pdv) => ({
        pdvName: pdv.nombre,
        stores: pdv.stores.map((store) => ({
            storeName: store.nombre,
            equipmentTypes: store.teams.reduce((acc, team) => {
                const type = team.make.equipmenttype.nombre;
                if (!acc[type]) {
                    acc[type] = { teams: [], type, isOpen: ref(false) };
                }
                const teamWithAccessories = {
                    ...team,
                    isOpen: ref(false),
                    accessories: team.accessories || [],
                };
                acc[type].teams.push(teamWithAccessories);
                return acc;
            }, {}),
        })),
    }));
});

const toggleEquipmentType = (equipmentType) => {
    equipmentType.isOpen.value = !equipmentType.isOpen.value;
    if (equipmentType.isOpen.value) {
        // Esperar a que el DOM se actualice y luego observar cada canvas dentro del tipo de equipo
        nextTick(() => {
            equipmentType.teams.forEach((team) => {
                const elementId = `barcode-${team.id}`;
                const element = document.getElementById(elementId);
                if (element) {
                    const observer = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (entry.isIntersecting) {
                                    JsBarcode(
                                        `#${elementId}`,
                                        team.codigo_barras,
                                        {
                                            format: "CODE128",
                                            lineColor: "#000",
                                            width: 2,
                                            height: 30,
                                            displayValue: true,
                                        }
                                    );
                                    observer.disconnect(); // Desconectar después de generar el código de barras
                                }
                            });
                        },
                        { threshold: 0.1 }
                    );

                    observer.observe(element);
                } else {
                    console.error("Element not found:", elementId);
                }
            });
        });
    }
};
const toggleTeam = (team) => {
    team.isOpen.value = !team.isOpen.value;
};
</script>

<template>
    <div class="">
        <div class="px-2">
            <div
                v-for="pdv in groupedByPdv"
                :key="pdv.pdvName"
                class="rounded-xl my-3 p-4 shadow-abajo-1 bg-3D-50"
            >
                <h2
                    class="text-xl sm:text-2xl font-extrabold text-gray-600 inline-block"
                >
                    PDV: {{ pdv.pdvName }}
                </h2>
                <div v-for="store in pdv.stores" :key="store.storeName">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-600">
                        Tienda: {{ store.storeName }}
                    </h3>
                    <div
                        v-for="(data, type) in store.equipmentTypes"
                        :key="type"
                        class="my-2"
                    >
                        <div
                            class="flex items-center bg-sky-100 text-gray-600 font-bold rounded-md focus:outline-none focus:ring-2 shadow-abajo-2"
                        >
                            <button
                                @click="toggleEquipmentType(data)"
                                class="text-left w-full py-3 px-4"
                            >
                                <v-icon
                                    name="hi-chevron-right"
                                    class="text-gray-600"
                                    :class="
                                        data.isOpen.value
                                            ? 'rotate-90 duration-500'
                                            : 'rotate-0 duration-500'
                                    "
                                />
                                Tipo de Equipo: {{ type }}
                            </button>
                        </div>
                        <ul
                            v-if="data.isOpen.value"
                            class="mt-2 mb-4 divide-gray-300 space-y-2"
                        >
                            <li
                                v-for="team in data.teams"
                                :key="team.id"
                                :class="teamStatusBackground(team.estado)"
                                class="ml-6 py-2 px-3 shadow-abajo-2 rounded-md"
                            >
                                <div class="flex justify-between">
                                    <button
                                        @click="toggleTeam(team)"
                                        class="w-full text-left text-sm sm:text-md font-bold text-gray-600"
                                    >
                                        <v-icon
                                            name="hi-chevron-right"
                                            class="text-gray-600"
                                            :class="
                                                team.isOpen.value
                                                    ? 'rotate-90 duration-500'
                                                    : 'rotate-0 duration-500'
                                            "
                                        />
                                        {{ team.make.nombre }} ->
                                        {{ team.nombre }} (Estado:
                                        {{ team.estado }})
                                    </button>
                                    <div class="flex justify-center gap-2 mt-2">
                                        <div class="relative group">
                                            <button
                                                class="bg-yellow-100 hover:bg-yellow-200 text-white font-bold py-1 px-2 rounded-full shadow-abajo-1 focus:ring-1 focus:ring-sky-600"
                                                @click="
                                                    $emit('edit-team', team)
                                                "
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
                                                class="bg-purple-100 hover:bg-purple-200 text-white font-bold py-1 px-2 rounded-full shadow-abajo-1 focus:ring-1 focus:ring-sky-600"
                                                @click="
                                                    downloadPdf(
                                                        `barcode-${team.id}`,
                                                        `${pdv.pdvName}/${store.storeName}/${team.nombre}`
                                                    )
                                                "
                                            >
                                                <v-icon
                                                    class="text-gray-600"
                                                    name="hi-solid-download"
                                                />
                                            </button>
                                            <span
                                                class="absolute bottom-full mb-2 hidden group-hover:block w-40 h-14 p-2 text-xs text-white bg-sky-950 rounded-md z-50 -translate-x-1/2 -left-8"
                                            >
                                                <canvas
                                                    :id="'barcode-' + team.id"
                                                    class="w-36 rounded-md"
                                                ></canvas>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="team.isOpen.value"
                                    class="flex space-x-2 pl-8 pt-2"
                                >
                                    <button
                                        v-for="accessory in team.accessories"
                                        :key="accessory.id"
                                        class="bg-gray-200 hover:bg-gray-300 text-black font-medium rounded"
                                    >
                                        <h5
                                            class="block py-1 px-2 shadow-abajo-2 hover:shadow-abajo-2-cambio rounded-md bg-sky-100 text-xs sm:text-sm text-gray-600 font-extrabold"
                                            @click="
                                                $emit(
                                                    'view-accessory',
                                                    accessory
                                                )
                                            "
                                        >
                                            {{ accessory.nombre }}
                                        </h5>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
