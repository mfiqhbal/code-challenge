<template>
  <div class="container mx-auto">
    <div class="flex justify-center">
      <div class="w-full max-w-lg">
        <div class="card bg-white shadow-md rounded-lg">
          <div class="card-body p-6">
            <form @submit.prevent="uploadFile">
              <div class="mb-4">
                <div v-if="alert.message" :class="alert.type" class="alert">
                  {{ alert.message }}
                </div>
                <div class="flex justify-between items-center mb-4">
                  <label for="fileInput" class="form-label text-lg font-semibold">Upload From File</label>
                  <a href="/sample-template.xlsx" class="text-blue-600" download>Download Excel Template</a>
                </div>
                <input
                  class="form-control border rounded-lg px-3 py-2 w-full"
                  type="file"
                  id="fileInput"
                  @change="handleFileUpload"
                />
                <div v-if="errors.file" class="mt-1 text-red-500">{{ errors.file }}</div>
              </div>
              <div class="mt-4 flex justify-between">
                <button type="submit" class="btn btn-primary bg-blue-600 text-white py-2 px-4 rounded-lg">Upload</button>
                <button type="button" class="btn btn-danger bg-red-600 text-white py-2 px-4 rounded-lg" @click="cancelUpload">Cancel</button>
              </div>
            </form>
          </div>
        </div>

        <div class="card mt-4 bg-white shadow-md rounded-lg">
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

              <!-- Dropdown for Class Filter -->
              <div class="mb-4">
                <label for="classSelect" class="form-label text-lg font-semibold">Filter by Class</label>
                <select
                  id="classSelect"
                  v-model="selectedClass"
                  class="form-control border rounded-lg px-3 py-2 w-full"
                  @change="filterByClass"
                >
                  <option value="">All Classes</option>
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
                      <td>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="flex justify-center mt-4">
                  <!-- Pagination Component -->
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
import Pagination from './components/Pagination.vue';

export default {
  components: {
    Pagination
  },
  data() {
    return {
      alert: {
        message: null,
        type: null,
      },
      errors: {},
      searchQuery: '',
      selectedClass: '',
      classes: [], // List of available classes
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
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        this.students = response.data;
        this.totalStudents = this.students.length; // Update total students after upload
        this.paginateStudents(); // Re-paginate after upload
        this.fetchClasses(); // Fetch available classes
      } catch (error) {
        console.error('Error uploading file:', error);
      }
    },
    async fetchClasses() {
      // Fetch available classes (replace this with your API endpoint if necessary)
      const response = await axios.get('/api/classes');
      this.classes = response.data;
    },
    async fetchStudents(page = 1) {
      const response = await axios.get('/api/students');
      this.students = response.data;
      this.totalStudents = this.students.length;
      this.currentPage = page; // Update the current page
      this.paginateStudents(); // Call pagination function
    },
    paginateStudents() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      this.paginatedStudents = this.students.slice(start, end);
    },
    async searchStudents() {
      const response = await axios.get('/api/students');
      this.students = response.data.filter(student => 
        (student.name && student.name.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
        (student.level !== undefined && String(student.level).includes(this.searchQuery)) ||
        (student.class && student.class.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
        (student.parent_contact && student.parent_contact.toLowerCase().includes(this.searchQuery.toLowerCase()))
      );
      this.totalStudents = this.students.length;
      this.currentPage = 1; // Reset to the first page on search
      this.paginateStudents(); // Re-paginate after search
    },
    filterByClass() {
      // Filter students by the selected class
      this.fetchStudents();
      this.paginateStudents();
    },
  },
  mounted() {
    this.fetchStudents();
    this.fetchClasses(); // Fetch classes on mount
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
