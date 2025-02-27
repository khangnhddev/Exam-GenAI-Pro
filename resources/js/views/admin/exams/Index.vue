<template>
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">Exams</h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Manage certification exams and their questions
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <router-link :to="{ name: 'admin.exams.create' }"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Exam
                    </router-link>
                </div>
            </div>

            <!-- Exam List -->
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status
                                        </th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Questions
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="exam in exams" :key="exam.id">
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ exam.title }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <span :class="[
                                                'inline-flex rounded-full px-2 text-xs font-semibold leading-5',
                                                exam.status === 'published'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-yellow-100 text-yellow-800'
                                            ]">
                                                {{ exam.status }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ exam.questions_count || 0 }}
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <router-link
                                                :to="{ name: 'admin.exams.questions', params: { examId: exam.id } }"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4">
                                                Questions
                                            </router-link>
                                            <router-link :to="{ name: 'admin.exams.edit', params: { id: exam.id } }"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4">
                                                Edit
                                            </router-link>
                                            <button @click="deleteExam(exam)" class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const exams = ref([])

onMounted(async () => {
    try {
        const { data } = await axios.get('/admin/exams');
        exams.value = data.data;
    } catch (error) {
        console.error('Error loading exams:', error)
    }
})

async function deleteExam(exam) {
    if (!confirm('Are you sure you want to delete this exam?')) return

    try {
        await axios.delete(`/api/v1/admin/exams/${exam.id}`)
        exams.value = exams.value.filter(e => e.id !== exam.id)
    } catch (error) {
        console.error('Error deleting exam:', error)
    }
}
</script>