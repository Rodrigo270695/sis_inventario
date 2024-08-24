<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import SubmitButton from "@/Components/SubmitButton.vue";
import TitleForm from "@/Components/TitleForm.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    accesory: Object,
    stores: Array,
    makes: Array,
});

const form = useForm({
    id: props.accesory ? props.accesory.id : "",
    nombre: props.accesory ? props.accesory.nombre : "",
    modelo: props.accesory ? props.accesory.modelo : "",
    serie: props.accesory ? props.accesory.serie : "",
    capacidad: props.accesory ? props.accesory.capacidad : "",
    fecha_compra: props.accesory ? props.accesory.fecha_compra : "",
    garantia_tienda: props.accesory ? String(props.accesory.garantia_tienda) : "0",
    garantia_marca: props.accesory ? String(props.accesory.garantia_marca) : "0",
    descripcion: props.accesory ? props.accesory.descripcion : "",
    documento: "",
    documento_descarga: props.accesory ? props.accesory.documento : "",
    make_id: props.accesory ? props.accesory.make_id : "",
    store_id: props.accesory ? props.accesory.store_id : "",
});

const onFileChange = (event) => {
    form.documento = event.target.files[0];
};

const submit = () => {
    if (props.accesory) {
        form.put(route("accessory.update", props.accesory.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("accessory.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);
</script>

<template>
    <TitleForm :title="form.id == 0 ? 'Registrar Accesorio' : 'Actualizar Accesorio'" @close-modal="emit('close-modal')" />
    <div class="pt-2 bg-3D-50">
        <form @submit.prevent="submit">
            <div class="bg-3D-50 shadow-md rounded-md p-4">
                <div class="mb-4">
                    <div class="grid grid-cols-6 gap-3">
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Almacén" required/>
                            <select
                                v-model="form.store_id"
                                class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                            >
                                <option disabled selected value="">
                                    Elija una opción
                                </option>
                                <option v-for="store in stores" :key="store.id" :value="store.id">
                                    {{ store.nombre }}
                                </option>
                            </select>
                            <InputError class="w-full" :message="form.errors.store_id" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Marca" required/>
                            <select
                                v-model="form.make_id"
                                class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                            >
                                <option disabled selected value="">
                                    Elija una opción
                                </option>
                                <option v-for="make in makes" :key="make.id" :value="make.id">
                                    {{ make.nombre }}
                                </option>
                            </select>
                            <InputError class="w-full" :message="form.errors.make_id" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Nombre" required/>
                            <TextInput class="w-full" v-model="form.nombre" />
                            <InputError class="w-full" :message="form.errors.nombre" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Modelo" />
                            <TextInput class="w-full" v-model="form.modelo" />
                            <InputError class="w-full" :message="form.errors.modelo" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Serie" />
                            <TextInput class="w-full" v-model="form.serie" />
                            <InputError class="w-full" :message="form.errors.serie" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <InputLabel value="Capacidad" />
                            <TextInput class="w-full" v-model="form.capacidad" />
                            <InputError class="w-full" :message="form.errors.capacidad" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel value="Fecha de Compra" required/>
                            <TextInput type="date" class="w-full" v-model="form.fecha_compra" />
                            <InputError class="w-full" :message="form.errors.fecha_compra" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel value="Garantía Tienda (Meses)" required/>
                            <TextInput type="number" class="w-full" v-model="form.garantia_tienda" />
                            <InputError class="w-full" :message="form.errors.garantia_tienda" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel value="Garantía Marca (Meses)" />
                            <TextInput type="number" class="w-full" v-model="form.garantia_marca" />
                            <InputError class="w-full" :message="form.errors.garantia_marca" />
                        </div>
                        <div class="col-span-6 sm:col-span-12">
                            <InputLabel value="Descripción" />
                            <TextArea class="w-full h-20" v-model="form.descripcion" />
                            <InputError class="w-full" :message="form.errors.descripcion" />
                        </div>
                        <div v-if="!form.id" :class="form.documento ? 'col-span-11 sm:col-span-11' : 'col-span-12 sm:col-span-12'">
                            <InputLabel value="Documento" />
                            <TextInput
                                type="file"
                                class="w-full file:bg-transparent file:border-none file:px-1 file:mt-2 file:p-0 file:shadow-abajo-1 file:rounded-md file:text-slate-500 file:font-bold file:hover:text-slate-500  file:cursor-pointer"
                                @change="onFileChange"
                                ref="documento"
                                accept=".jpg, .jpeg, .png, .pdf, .doc, .docx"
                            />
                            <InputError class="w-full" :message="form.errors.documento" />
                        </div>
                        <div :class="form.id ? 'col-span-3 sm:col-span-3 mt-0' : 'col-span-0 sm:col-span-0 hidden'">
                            <InputLabel value="Descargar Documento" />
                            <a v-if="form.documento_descarga" class="" :href="`/accessory/download/${props.accesory ? props.accesory.documento : ''}`" :download="props.accesory ? props.accesory.documento : ''">
                                <v-icon v-if="props.accesory && props.accesory.documento && props.accesory.documento.endsWith('.pdf')" class="" name="vi-file-type-pdf" scale="2" />
                                <v-icon v-else-if="props.accesory && ((props.accesory.documento && props.accesory.documento.endsWith('.docx')) || (props.accesory.documento && props.accesory.documento.endsWith('.doc')))" class="" name="vi-file-type-word" scale="2" />
                                <v-icon v-else-if="props.accesory && props.accesory.documento && (props.accesory.documento.endsWith('.png') || props.accesory.documento.endsWith('.jpg') || props.accesory.documento.endsWith('.jpeg'))" class="" name="vi-file-type-image" scale="2" />
                            </a>
                            <p v-else>
                                <v-icon name="vi-default-file" scale="2" />
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <SubmitButton :text="form.id == 0 ? 'Registrar' : 'Actualizar'" :processing="form.processing" />
                </div>
            </div>
        </form>
    </div>
</template>
