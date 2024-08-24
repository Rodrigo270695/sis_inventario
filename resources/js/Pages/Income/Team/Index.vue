<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import TextSearch from "@/Components/TextSearch.vue";
import TeamForm from "./TeamForm.vue";
import UpdateDocument from "./UpdateDocument.vue";
import ViewAccessory from "./ViewAccessory.vue";
import IndexHeader from "@/Components/IndexHeader.vue";
import CardTeam from "@/Components/CardTeam.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    pdvs: Object,
    makes: Array,
    stores: Array,
    texto: String,
});

const form = useForm({
    team: Object,
});

let teamObj = ref(null);
let accessoryObj = ref(null);
let showModal = ref(false);
let showModalDoc = ref(false);
let showModalViewA = ref(false);
let query = ref(props.texto);

const addDoc = (team) => {
    showModalDoc.value = true;
    teamObj.value = team;
};

const viewAccessory = (accessory) => {
    console.log(accessory);
    showModalViewA.value = true;
    accessoryObj.value = accessory;
};

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
    showModalViewA.value = false;
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
    <AppLayout title="Equipos">
        <template #header>
            <IndexHeader title="Gestionar Equipos" @reload="goToIndex" />
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <TextSearch
                        :query="query"
                        :search="search"
                        :add="addTeam"
                        @update:query="query = $event"
                        placeholder="Buscar Punto de venta"
                    />

                    <div class="p-2">
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <CardTeam :pdvs="pdvs" @edit-team="editTeam" @add-doc="addDoc" @view-accessory="viewAccessory" />
                            </div>
                        </div>

                        <Pagination class="mt-2" :pagination="pdvs" />
                    </div>
                    <Modal :show="showModal" maxWidth="3xl">
                        <TeamForm
                            :team="teamObj"
                            :makes="makes"
                            :stores="stores"
                            @close-modal="closeModal"
                        />
                    </Modal>
                    <Modal :show="showModalDoc" maxWidth="lg">
                        <UpdateDocument :team="teamObj" @close-modal="closeModal"/>
                    </Modal>
                    <Modal :show="showModalViewA" maxWidth="2xl">
                        <ViewAccessory  @close-modal="closeModal" :accessory="accessoryObj" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
