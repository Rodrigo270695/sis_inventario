<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import NavLink from "@/Components/NavLink.vue";
import ToastList from "@/Components/ToastList.vue";
import Navigation from "./Navigation.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const showZonal = ref(JSON.parse(localStorage.getItem('showZonal')) || false);
const showFabricante = ref(JSON.parse(localStorage.getItem('showFabricante')) || false);
const showIngresos = ref(JSON.parse(localStorage.getItem('showIngresos')) || false);
const showOperacion = ref(JSON.parse(localStorage.getItem('showOperacion')) || false);

const logout = () => {
    router.post(route("logout"));
};

watch(showZonal, (newValue) => {
    localStorage.setItem('showZonal', JSON.stringify(newValue));
});

watch(showFabricante, (newValue) => {
    localStorage.setItem('showFabricante', JSON.stringify(newValue));
});

watch(showIngresos, (newValue) => {
    localStorage.setItem('showIngresos', JSON.stringify(newValue));
});

watch(showOperacion, (newValue) => {
    localStorage.setItem('showOperacion', JSON.stringify(newValue));
});

const menuItems = ref([
    {
        name: "Módulo zonal",
        icon: "ri-gps-fill",
        show: showZonal,
        toggle: () => (showZonal.value = !showZonal.value),
        subItems: [
            {
                name: "Zonales",
                icon: "md-addlocationalt-outlined",
                route: "zonal.index",
            },
            {
                name: "PDV's",
                icon: "md-gpsfixed-twotone",
                route: "pdv.index",
            },
            {
                name: "Almacenes",
                icon: "fa-store-alt",
                route: "store.index",
            },
        ],
    },
    {
        name: "Módulo Fabricante",
        icon: "md-factory",
        show: showIngresos,
        toggle: () => (showIngresos.value = !showIngresos.value),
        subItems: [
            {
                name: "Tipo de equipo",
                icon: "md-mergetype-twotone",
                route: "type.index",
            },
            {
                name: "Marca",
                icon: "ai-preregistered",
                route: "make.index",
            },
        ],
    },
    {
        name: "Módulo Ingreso",
        icon: "io-desktop",
        show: showFabricante,
        toggle: () => (showFabricante.value = !showFabricante.value),
        subItems: [
            {
                name: "Equipos",
                icon: "bi-pc-display",
                route: "team.index",
            },
            {
                name: "Accesorios",
                icon: "bi-mouse2",
                route: "accessory.index",
            },
        ],
    },
    {
        name: "Módulo Operación",
        icon: "hi-solid-document",
        show: showOperacion,
        toggle: () => (showOperacion.value = !showOperacion.value),
        subItems: [
            {
                name: "Tipo de solicitud",
                icon: "hi-solid-document-add",
                route: "typer.index",
            },
            {
                name: "Solicitudes",
                icon: "bi-send-plus",
                route: "solicitude.index",
            },
        ],
    },
    {
        name: "Módulo Usuario",
        icon: "fa-user-friends",
        show: showOperacion,
        toggle: () => (showOperacion.value = !showOperacion.value),
        subItems: [
            {
                name: "Usuarios",
                icon: "hi-user-add",
                route: "user.index",
            },
        ],
    },
]);

</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <ToastList />

        <Navigation :title="title" :menuItems="menuItems" :showingNavigationDropdown="showingNavigationDropdown" @toggle-dropdown="showingNavigationDropdown = !showingNavigationDropdown" />

        <aside
            :class="{
                '-translate-x-full': !showingNavigationDropdown,
                'translate-x-0': showingNavigationDropdown,
            }"
            class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-3D-50 border-r border-gray-200 md:translate-x-0 shadow-abajo-1"
            aria-label="Sidebar"
        >
            <div class="h-full px-3 pb-4 overflow-y-auto bg-3D-50">
                <ul class="space-y-3 font-medium p-2">
                    <li class="">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="flex items-center p-3 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                        >
                            <div class="mt-[3px] -mb-[6px] text-lg">
                                <v-icon
                                    name="md-dashboard-sharp"
                                    class="text-slate-500 hover:text-slate-600 mx-[6px]"
                                />
                                <span class="ms-2">Dashboard</span>
                            </div>
                        </NavLink>
                    </li>

                    <li v-for="item in menuItems" :key="item.name" class="shadow-abajo-2 rounded-lg">
                        <button
                            @click="item.toggle"
                            class="flex cursor-pointer justify-between items-center p-2 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                        >
                            <div class="">
                                <v-icon
                                    :name="item.icon"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <span class="ms-3">{{ item.name }}</span>
                            </div>
                            <div class="">
                                <v-icon
                                    v-if="!item.show"
                                    name="hi-solid-plus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                                <v-icon
                                    v-else
                                    name="hi-minus-sm"
                                    class="text-slate-500 hover:text-slate-600"
                                />
                            </div>
                        </button>
                        <div v-if="item.show" class="flex flex-col">
                            <NavLink
                                v-for="subItem in item.subItems"
                                :key="subItem.name"
                                :href="route(subItem.route)"
                                :active="route().current(subItem.route)"
                                class="rounded-lg"
                            >
                                <div class="pl-4 flex p-2 hover:bg-blue-100 w-full">
                                    <v-icon
                                        :name="subItem.icon"
                                        class="text-slate-500 hover:text-slate-600 "
                                    />
                                    <p class="text-slate-500 ml-3">{{ subItem.name }}</p>
                                </div>
                            </NavLink>
                        </div>
                    </li>
<!--                     <li class="">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="flex items-center p-3 text-slate-700 rounded-lg bg-3D-50 hover:bg-blue-100 font-bold group w-full shadow-abajo-1 hover:shadow-abajo-2"
                        >
                            <div class="mt-[3px] -mb-[6px] text-lg">
                                <v-icon
                                    name="hi-solid-document-report"
                                    class="text-slate-500 hover:text-slate-600 mx-[6px]"
                                />
                                <span class="ms-2">Reportes</span>
                            </div>
                        </NavLink>
                    </li> -->
                </ul>
            </div>
        </aside>

        <div class="p-5 md:ml-64">
            <header v-if="$slots.header" class="shadow-abajo-1 rounded-lg">
                <div class="mt-14 rounded-lg bg-3D-50 p-4">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
    opacity: 0;
}
</style>
