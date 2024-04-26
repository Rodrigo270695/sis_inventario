<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import CardTeam from "@/Components/CardTeam.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    pdvs: Array,
    makes: Array,
    stores: Array,
    texto: String,
});

const form = useForm({
    team: Object,
});

let teamObj = ref(null);
let showModal = ref(false);
let showModalDoc = ref(false);
let query = ref(props.texto);

const addDoc = (team) => {
    showModalDoc.value = true;
    teamObj.value = team
}

const addTeam = () => {
    teamObj.value = null;
    showModal.value = true;
};

const editTeam = (team) => {
    teamObj.value = team;
    showModal.value = true;
};

const closeModal = () => {
    showModalDoc.value = false;
    showModal.value = false;
    teamObj.value = null;
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

/* const changeStatus = (team) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de del team?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("team.change", team), {
                preserveScroll: true,
            });
        }
    });
}; */

const deleteTeam = (team) => {
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
            form.delete(route("team.destroy", team), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("team.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("team.index"));
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
                                @input="query = query.toUpperCase()"
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
                                @click="addTeam"
                            >
                                <v-icon
                                    name="io-add-circle-sharp"
                                    scale="1.1"
                                />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 bg-3D-50">

                        <div class="block">
                            <div class="overflow-x-auto rounded-lg">

                            </div>
                        </div>

                        <Pagination class="mt-2" :pagination="pdvs" />
                    </div>
<!--                     <Modal :show="showModal" maxWidth="3xl">
                        <TeamForm
                            :team="teamObj"
                            :makes="makes"
                            :stores="stores"
                            @close-modal="closeModal"
                        />
                    </Modal>
                    <Modal :show="showModalDoc" maxWidth="lg">
                        <UpdateDocument :team="teamObj" @close-modal="closeModal"/>
                    </Modal> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
