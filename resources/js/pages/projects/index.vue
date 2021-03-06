<template>
    <div>
        <nav class="flex items-center mb-8 font-semibold">
            Projects
        </nav>

        <div class="w-full">
            <div class="flex flex-col items-start w-full mb-8 overflow-hidden bg-white rounded shadow-sm">
                 <div class="w-full px-5 py-4 font-semibold bg-gray-50">
                    <div class="flex items-center">
                        <h2>All projects</h2>
                        <div class="mx-auto"></div>
                        <inertia-link :href="$route('projects.create')" class="inline-block px-4 py-2 text-sm font-semibold text-white transition duration-200 ease-in-out bg-pink-500 rounded hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-pink-500">
                            <i class="mr-1 opacity-50 fas fa-plus"></i> Add project
                        </inertia-link>
                    </div>
                </div>
                <table class="w-full table-fixed">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-5 py-3 text-sm font-semibold text-center">Name</th>
                            <th class="hidden px-5 py-3 text-sm font-semibold text-center md:table-cell">Repository</th>
                            <th class="hidden px-5 py-3 text-sm font-semibold text-center md:table-cell">Branch</th>
                            <th class="px-5 py-3 text-sm font-semibold text-center">Last deployment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="project in projects.data" v-bind:key="project.id" class="cursor-pointer even:bg-gray-50 hover:bg-gray-100" @click="show(project.id)">
                            <td class="px-5 py-3 font-semibold">
                                <div class="flex flex-row items-center w-full">
                                    <img :src="project.favicon_url" class="inline w-5 h-5 mr-2" v-if="project.live_url">
                                    <div class="truncate">{{ project.name }}</div>
                                </div>
                            </td>
                            <td class="hidden px-5 py-3 truncate md:table-cell">
                                <img :src="'https://github.com/' + project.repository.split('/')[0] + '.png'" class="inline w-6 h-6 mr-1 rounded ">
                                {{ project.repository }}
                            </td>
                            <td class="hidden px-5 py-3 font-mono text-center truncate md:table-cell">{{ project.branch }}</td>
                            <td class="px-5 py-3 text-center truncate">
                                <template v-if="project.latest_deployment">
                                    {{ $moment(project.latest_deployment.created_at).format('L') }} {{ $moment(project.latest_deployment.created_at).format('LTS') }}
                                </template>
                                <template v-else>
                                    N/A
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="w-full px-5 py-4 text-sm bg-gray-50">
                    <div class="flex items-center justify-between">
                        <inertia-link
                            v-if="projects.prev_page_url"
                            preserve-scroll
                            :href="projects.prev_page_url"
                            class="inline-block px-4 py-2 text-sm text-gray-500 transition duration-200 ease-in-out bg-gray-200 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-gray-500"
                        >
                            <i class="fas fa-arrow-left"></i>
                        </inertia-link>
                        <div v-else></div>

                        <div class="text-sm text-gray-500">
                            Displaying page {{ projects.current_page }} / {{ projects.last_page }}
                        </div>

                        <inertia-link
                            preserve-scroll
                            v-if="projects.next_page_url"
                            :href="projects.next_page_url"
                            class="inline-block px-4 py-2 text-sm text-gray-500 transition duration-200 ease-in-out bg-gray-200 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 focus:ring-gray-500"
                        >
                            <i class="fas fa-arrow-right"></i>
                        </inertia-link>
                        <div v-else></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        layout: require('../../layouts/app').default,

        props: {
            projects: Object,
        },

        methods: {
            show(project_id) {
                this.$inertia.visit(this.$route('projects.show', project_id))
            }
        }
    }
</script>
