<script setup>
import { defineProps, computed, ref } from "vue";

const props = defineProps({
    pdvs: Array,
});

const desgloce = ref(true);

const teamStatusBackground = (status) => {
    switch (status) {
        case "BAJA":
            return "bg-red-100 text-black";
        case "DESECHADO":
            return "bg-violet-100 text-black";
        case "MANTENIMIENTO-DAÑO":
            return "bg-yellow-100 text-black";
        case "MANTENIMIENTO-GARANTIA":
            return "bg-amber-100 text-black";
        case "RESERVADO":
            return "bg-purple-100 text-black";
        case "USO":
            return "bg-green-100 text-black";
        default:
            return "bg-gray-50 text-black";
    }
};

const groupedByPdv = computed(() => {
    return props.pdvs.data.map(pdv => ({
        pdvName: pdv.nombre,
        stores: pdv.stores.map(store => ({
            storeName: store.nombre,
            equipmentTypes: store.teams.reduce((acc, team) => {
                const type = team.make.equipmenttype.nombre;
                if (!acc[type]) {
                    acc[type] = { teams: [], type, isOpen: ref(false) };
                }
                const teamWithAccessories = {
                    ...team,
                    isOpen: ref(false),
                    accessories: team.accessories || []
                };
                acc[type].teams.push(teamWithAccessories);
                return acc;
            }, {})
        }))
    }));
});

const toggleEquipmentType = (equipmentType) => {
    equipmentType.isOpen.value = !equipmentType.isOpen.value;
    desgloce.value = !desgloce.value;
};

const toggleTeam = (team) => {
    team.isOpen.value = !team.isOpen.value;
};

</script>

<template>
    <div class="pt-2 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div v-for="pdv in groupedByPdv" :key="pdv.pdvName" class="rounded-xl mb-8 p-6 shadow-abajo-1 bg-3D-50">
                <h2 class="text-2xl font-bold text-gray-800 inline-block mb-4">
                    PDV: {{ pdv.pdvName }}
                </h2>
                <div v-for="store in pdv.stores" :key="store.storeName">
                    <h3 class="text-xl font-semibold text-gray-700 ">
                        Tienda: {{ store.storeName }}
                    </h3>
                    <div v-for="(data, type) in store.equipmentTypes" :key="type" class="mt-4">
                        <div class="flex items-center bg-blue-200 text-gray-800 font-bold uppercase rounded-md hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50 transition-colors duration-300">
                            <button @click="toggleEquipmentType(data)" class="text-left w-full py-3 px-4">
                                <v-icon v-if="desgloce" name="hi-chevron-right" class="text-gray-600" />
                                <v-icon v-else name="hi-chevron-down" class="text-gray-600" />
                                Tipo de Equipo: {{ type }}
                            </button>
                        </div>
                        <ul v-if="data.isOpen.value" class="mt-2 divide-y divide-gray-300 ">
                            <li v-for="team in data.teams" :key="team.id" :class="teamStatusBackground(team.estado)" class="py-2 rounded-md ml-4">
                                <button @click="toggleTeam(team)" class="w-full text-left">
                                    {{ team.make.nombre }} -> {{ team.nombre }} (Estado: {{ team.estado }})
                                </button>
                                <div v-if="team.isOpen.value" class="flex space-x-2 pl-8 pt-2">
                                    <button v-for="accessory in team.accessories" :key="accessory.id" class="bg-gray-200 hover:bg-gray-300 text-black font-medium py-1 px-2 rounded">
                                        {{ accessory.nombre }}
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
