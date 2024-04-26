<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    team: Object,
});

const form = useForm({
    _method: "PUT",
    documento: null,
});

const onFileChange = (event) => {
    form.documento = event.target.files[0];
};

const submit = () => {
    const formData = new FormData();
    formData.append("documento", form.documento);
    formData.append("_method", "PUT");

    form.post(route("team.update.document", props.team.id), formData, {
        preserveScroll: true,
        onSuccess: () => {
        }
    });
    emit("close-modal");
};

const emit = defineEmits(["close-modal"]);
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg text-slate-700 font-bold inline-flex items-center">
            Cargar Documento
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
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-9 sm:col-span-9">
                        <InputLabel value="Documento" />
                        <input
                            type="file"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                            @change="onFileChange"
                            ref="documento"
                            accept=".jpg, .jpeg, .png, .pdf, .doc, .docx"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.documento"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-3">
                        <InputLabel value="Documento" />
                        <a
                            v-if="team.documento"
                            class=""
                            :href="`/team/download/${team.documento}`"
                            :download="team.documento"
                        >
                            <v-icon
                                v-if="
                                    team.documento &&
                                    team.documento.endsWith('.pdf')
                                "
                                class=""
                                name="vi-file-type-pdf"
                                scale="2"
                            />
                            <v-icon
                                v-else-if="
                                    (team.documento &&
                                        team.documento.endsWith('.docx')) ||
                                    (team.documento &&
                                        team.documento.endsWith('.doc'))
                                "
                                class=""
                                name="vi-file-type-word"
                                scale="2"
                            />
                            <v-icon
                                v-else-if="
                                    team.documento &&
                                    (team.documento.endsWith('.png') ||
                                        team.documento.endsWith('.jpg') ||
                                        team.documento.endsWith('.jpeg'))
                                "
                                class=""
                                name="vi-file-type-image"
                                scale="2"
                            />
                        </a>
                        <p v-else>
                            <v-icon name="vi-default-file" scale="2" />
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-sky-800 hover:bg-sky-700 text-white px-4 py-2 rounded-md mr-2"
                    :disabled="form.processing"
                >
                    {{ form.id == 0 ? "Registrar" : "Actualizar" }}
                </button>
            </div>
        </div>
    </form>
</template>
