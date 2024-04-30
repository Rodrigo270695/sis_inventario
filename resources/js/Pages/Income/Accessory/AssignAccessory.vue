<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, defineProps, defineEmits } from "vue";

const props = defineProps({
    teams: Array,
    accessory: Object,
});

const form = useForm({
    team_id: "",
    accessory_id: props.accessory ? props.accessory.id : "",
});

const submit = () => {
    form.post(route("accessory.assignToTeam"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const filteredTeams = computed(() => {
    return props.teams.filter(team => team.store_id === props.accessory.store_id);
});

const emit = defineEmits(["close-modal"]);
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center">
            Asignar a un equipo
        </div>
        <button @click="emit('close-modal')">
            <v-icon
                class="text-white rounded-md bg-red-400"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Equipo" />
                        <select
                            id="select"
                            v-model="form.team_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="team in filteredTeams"
                                :key="team.id"
                                :value="team.id"
                            >
                                {{ team.store.pdv.nombre }}->{{
                                    team.store.nombre
                                }}->{{ team.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.team_id"
                        />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button
                        class="bg-sky-800 hover:bg-sky-700 text-white px-4 py-2 rounded-md mt-3"
                        :disabled="form.processing"
                    >
                        Registrar
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
