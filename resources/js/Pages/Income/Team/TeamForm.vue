<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";

const props = defineProps({
    team: Object,
    stores: Array,
    makes: Array,
});

const form = useForm({
    id: props.team ? props.team.id : "",
    nombre: props.team ? props.team.nombre : "",
    modelo: props.team ? props.team.modelo : "",
    ip: props.team ? props.team.ip : "",
    serie: props.team ? props.team.serie : "",
    procesador: props.team ? props.team.procesador : "",
    mac: props.team ? props.team.mac : "",
    fecha_compra: props.team ? props.team.fecha_compra : "",
    garantia_tienda: props.team ? props.team.garantia_tienda : "",
    garantia_marca: props.team ? props.team.garantia_marca : "",
    descripcion: props.team ? props.team.descripcion : "",
    documento: props.team ? props.team.documento : "",
    codigo_barras: props.team ? props.team.codigo_barras : "123456789",
    make_id: props.team ? props.team.make_id : "",
    store_id: props.team ? props.team.store_id : "",
});

const submit = () => {
    if (props.team) {
        form.put(route("team.update", props.team.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("team.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);
</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg text-slate-700 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Equipo" : "Actualizar Equipo" }}
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
                    <div class="col-span-12 sm:col-span-6">
                        <InputLabel value="Almacén" />
                        <select
                            id="select"
                            v-model="form.store_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="store in stores"
                                :key="store.id"
                                :value="store.id"
                            >
                            {{ store.pdv.zonal.nombre }}-{{ store.pdv.nombre }}-{{ store.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.store_id"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <InputLabel value="Nombre" />
                        <TextInput
                            class="w-full"
                            v-model="form.nombre"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.nombre"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <InputLabel value="Marca" />
                        <select
                            id="select"
                            v-model="form.make_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="make in makes"
                                :key="make.id"
                                :value="make.id"
                            >
                            {{ make.equipmenttype.nombre }}-{{ make.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.make_id"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <InputLabel value="Modelo" />
                        <TextInput
                            class="w-full"
                            v-model="form.modelo"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.modelo"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <InputLabel value="Serie" />
                        <TextInput
                            class="w-full"
                            v-model="form.serie"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.serie"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <InputLabel value="Procesador" />
                        <TextInput
                            class="w-full"
                            v-model="form.procesador"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.procesador"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-4">
                        <InputLabel value="Ip" />
                        <TextInput
                            class="w-full"
                            v-model="form.ip"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.ip"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <InputLabel value="Mac" />
                        <TextInput
                            class="w-full"
                            v-model="form.mac"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.mac"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <InputLabel value="Fecha de Compra" />
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.fecha_compra"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.fecha_compra"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <InputLabel value="Garantía Tienda(Mes)" />
                        <TextInput
                            type="number"
                            class="w-full"
                            v-model="form.garantia_tienda"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.garantia_tienda"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-3">
                        <InputLabel value="Garantía Marca(Mes)" />
                        <TextInput
                            type="number"
                            class="w-full"
                            v-model="form.garantia_marca"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.garantia_marca"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <InputLabel value="Descripción" />
                        <TextArea class="w-full" v-model="form.descripcion" />
                        <InputError
                            class="w-full"
                            :message="form.errors.descripcion"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <InputLabel value="Documento" />
                        <input type="file"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                            @change="onFileChange" ref="documento" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx" />
                        <InputError
                            class="w-full"
                            :message="form.errors.documento"
                        />
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
