<template>
    <div class="mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                <div class="card bg-white shadow-md rounded-lg p-4" style="max-width: 800px;">
                    <div class="card-body">
                        <form @submit.prevent="uploadFile">
                            <div class="mb-4">
                                <div v-if="alert.message" :class="alert.type" class="alert">
                                    {{ alert.message }}
                                </div>
                                <label for="fileInput" class="form-label text-lg font-semibold ml-4 text-blue-500">Upload From File</label>
                                <div class="mb-2">
                                    <a href="/sample-template.xlsx" class="text-blue-600" download>Download Excel Template</a>  
                                </div>
                                <input
                                    class="form-control flex justify-center items-center h-10 bg-gray-100 border rounded"
                                    type="file"
                                    id="fileInput"
                                    @change="handleFileUpload"
                                />
                                <div v-if="errors.file" class="mt-1 text-red-500">{{ errors.file }}</div>
                            </div>
                            <div class="mt-4 mb-4 flex justify-between">
                                <button type="submit" class="btn btn-primary border border-blue-600 text-blue-600 py-1 px-4 rounded-lg text-sm">Upload</button>
                                <button type="button" class="btn btn-danger border border-red-600 text-red-600 py-1 px-4 rounded-lg text-sm" @click="cancelUpload">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4 bg-white shadow-md rounded-lg p-4" style="max-width: 800px;">
                    <div class="p-4">
                        <form @submit.prevent="searchStudents">
                            <div class="input-group flex mb-4">
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    class="form-control border rounded-l-lg px-3 py-2 w-full"
                                    placeholder="Search student by anything"
                                    @input="searchStudents"
                                />
                                <button class="btn btn-success bg-green-600 text-white px-4 rounded-r-lg" type="submit">Search</button>
                            </div>
                            <div class="mb-4">
                                <label for="classSelect" class="form-label text-lg font-semibold">Filter by Class</label>
                                <select
                                    id="classSelect"
                                    v-model="selectedClass"
                                    class="form-control border rounded-lg px-3 py-2 w-full"
                                    @change="filterByClass"
                                >
                                    <option value="all">All Classes</option>
                                    <option v-for="classOption in classes" :key="classOption" :value="classOption">
                                        {{ classOption }}
                                    </option>
                                </select>
                            </div>
                        </form>

                        <div class="card-body">
                            <p v-if="!students.length">No student found.</p>
                            <div v-else>
                                <table class="table-auto w-full text-left">
                                    <caption class="mb-2 text-lg font-semibold">
                                        List of {{ totalStudents }} students
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Level</th>
                                            <th>Class</th>
                                            <th>Parent Contact</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(student, index) in paginatedStudents" :key="student.id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ student.name }}</td>
                                            <td>{{ student.level }}</td>
                                            <td>{{ student.class }}</td>
                                            <td>{{ student.parent_contact }}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="flex justify-center mt-4">
                                    <Pagination :total="totalStudents" :currentPage="currentPage" @page-changed="fetchStudents"></Pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Pagination from '../components/Pagination.vue';

export default {
    components: { Pagination },
    data() {
        return {
            alert: { message: null, type: null },
            errors: {},
            searchQuery: '',
            selectedClass: 'all',
            classes: [],
            students: [],
            paginatedStudents: [],
            totalStudents: 0,
            currentPage: 1,
            perPage: 10,
            file: null,
        };
    },
    methods: {
        handleFileUpload(e) {
            this.file = e.target.files[0];
        },
        async uploadFile() {
            let formData = new FormData();
            formData.append('file', this.file);

            try {
                const response = await axios.post('/api/upload', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                this.students = response.data;
                this.totalStudents = this.students.length;
                this.fetchClasses();
                this.fetchStudents();
                window.location.reload();
            } catch (error) {
                console.error('Error uploading file:', error);
            }
        },
        async fetchClasses() {
            const response = await axios.get('/api/classes');
            this.classes = response.data;
        },
        async fetchStudents(page = 1) {
            const response = await axios.get(`/api/students/class/${this.selectedClass}`);
            this.students = response.data;
            this.totalStudents = this.students.length;
            this.currentPage = page;
            this.paginateStudents();
        },
        paginateStudents() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            this.paginatedStudents = this.students.slice(start, end);
        },
        async searchStudents() {
            this.selectedClass = 'all';
            const response = await axios.get('/api/students');
            this.students = response.data.filter(student => 
                (student.name && student.name.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (student.level !== undefined && String(student.level).includes(this.searchQuery)) ||
                (student.class && student.class.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                (student.parent_contact && student.parent_contact.toLowerCase().includes(this.searchQuery.toLowerCase()))
            );
            this.totalStudents = this.students.length;
            this.currentPage = 1;
            this.paginateStudents();
        },
        async filterByClass() {
            this.currentPage = 1;
            await this.fetchStudents(this.currentPage);
        },
    },
    mounted() {
        this.fetchStudents();
        this.fetchClasses();
    },
};
</script>

<style scoped>
.card {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.25rem;
}
</style>
