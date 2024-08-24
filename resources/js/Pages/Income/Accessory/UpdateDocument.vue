<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    accessory: Object,
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

    form.post(route("accessory.update.document", props.accessory.id), formData, {
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
        <div class="text-lg sm:text-xl text-slate-700 font-bold inline-flex items-center">
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
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-9 sm:col-span-9">
                        <InputLabel value="Documento" />
                        <TextInput
                            type="file"
                            class="w-full file:bg-transparent file:border-none file:px-1 file:mt-2 file:p-0 file:shadow-abajo-1 file:rounded-md file:text-slate-500 file:font-bold file:hover:text-slate-500  file:cursor-pointer"
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
                            v-if="accessory && accessory.documento"
                            class=""
                            :href="`/accessory/download/${accessory.documento}`"
                            :download="accessory.documento"
                        >
                            <v-icon
                                v-if="
                                    accessory.documento &&
                                    accessory.documento.endsWith('.pdf')
                                "
                                class=""
                                name="vi-file-type-pdf"
                                scale="2"
                            />
                            <v-icon
                                v-else-if="
                                    (accessory.documento &&
                                        accessory.documento.endsWith('.docx')) ||
                                    (accessory.documento &&
                                        accessory.documento.endsWith('.doc'))
                                "
                                class=""
                                name="vi-file-type-word"
                                scale="2"
                            />
                            <v-icon
                                v-else-if="
                                    accessory.documento &&
                                    (accessory.documento.endsWith('.png') ||
                                        accessory.documento.endsWith('.jpg') ||
                                        accessory.documento.endsWith('.jpeg'))
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
                <SubmitButton :text="'Actualizar'" :processing="form.processing" />
            </div>
        </div>
    </form>
</template>
