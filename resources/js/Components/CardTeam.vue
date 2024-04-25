<template>
    <div class="pt-2">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            <div
                v-for="pdv in pdvs.data"
                :key="pdv.id"
                class="bg-3D-50 rounded-2xl shadow-abajo-2 mb-8 p-5 sizing"
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
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-600 ">
                        Tienda: {{ store.nombre }}
                    </h3>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 p-3"
                    >
                        <div
                            v-for="team in store.teams"
                            :key="team.id"
                            class="relative rounded-lg p-3 shadow-abajo-2"
                        >
                            <div class="flex justify-between items-center">
                                <h4
                                    class="text-base sm:text-lg font-medium text-gray-700 "
                                >
                                    {{ team.make.equipmenttype.nombre }}->{{ team.make.nombre }}
                                </h4>
                                <button
                                    class="bg-yellow-100 hover:bg-yellow-200 text-white font-bold py-1 px-2 rounded-full shadow-abajo-1"
                                    @click="$emit('edit-team', team)"
                                >
                                    <v-icon class="text-gray-600" name="md-modeedit-round" />
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <div
                                    v-for="accessory in team.accessories"
                                    :key="accessory.id"
                                    class="bg-blue-50 rounded-lg p-2 shadow-abajo-2 flex items-center justify-center  cursor-pointer hover:shadow-abajo-2-cambio"
                                >
                                    <h5 class="text-xs sm:text-sm font-extrabold text-slate-600">
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

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    pdvs: Array,
});
</script>

<style scope>
.sizing {
    box-sizing: border-box;
}
</style>
