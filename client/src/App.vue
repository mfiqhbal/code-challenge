<template>
  <div>
      <form @submit.prevent="uploadFile">
          <input type="file" @change="onFileChange" />
          <button type="submit">Upload</button>
      </form>
      <div v-if="students.length > 0">
          <table>
              <thead>
                  <tr>
                      <th>Name</th>
                      <th>Level</th>
                      <th>Class</th>
                      <th>Parent Contact</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="student in students" :key="student.id">
                      <td>{{ student.name }}</td>
                      <td>{{ student.level }}</td>
                      <td>{{ student.class }}</td>
                      <td>{{ student.parent_contact }}</td>
                  </tr>
              </tbody>
          </table>
      </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
      return {
          file: null,
          students: [
            {
              name: 'student name',
              level: '1',
              class: 'berlian',
              parent_contact: '0123456789',
            }
          ],
      };
  },
  methods: {
      onFileChange(e) {
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
          } catch (error) {
              console.error('Error uploading file:', error);
          }
      }
  }
};
</script>
