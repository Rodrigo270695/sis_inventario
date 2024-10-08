<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Dropdown from "@/Components/Dropdown.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { defineProps, watch } from "vue";

const props = defineProps({
    title: String,
    menuItems: Array,
    showingNavigationDropdown: Boolean,
});

const logout = () => {
    router.post(route("logout"));
};

const toggleDropdown = () => {
    emit('toggle-dropdown');
};

const emit = defineEmits(['toggle-dropdown']);

</script>

<template>
    <nav class="fixed top-0 z-50 w-full bg-3D-50 border-b border-gray-200 shadow-abajo-1">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button
                        @click="toggleDropdown"
                        type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden bg-3D-50 focus:outline-none focus:ring-2 focus:ring-gray-200"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>
                    </button>
                    <Link :href="route('dashboard')" class="flex ms-2 md:me-24">
                        <ApplicationMark class="block h-9 w-auto" />
                    </Link>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    v-if="$page.props.jetstream.managesProfilePhotos"
                                    class="flex text-sm bg-3D-50 rounded-full focus:ring-4 focus:ring-gray-300"
                                    aria-expanded="false"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <img
                                        class="w-8 h-8 rounded-full"
                                        :src="$page.props.auth.user.profile_photo_url"
                                        :alt="$page.props.auth.user.name"
                                    />
                                </button>
                                <span v-else class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-3D-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                    >
                                        {{ $page.props.auth.user.name }}
                                        <svg
                                            class="ms-2 -me-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <template #content>
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Gestionar cuenta
                                </div>
                                <ResponsiveNavLink :href="route('profile.show')">
                                    Perfil
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    v-if="$page.props.jetstream.hasApiFeatures"
                                    :href="route('api-tokens.index')"
                                >
                                    API Tokens
                                </ResponsiveNavLink>
                                <div class="border-t border-gray-200" />
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">
                                        Salir
                                    </ResponsiveNavLink>
                                </form>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
