<template>
  <div>
    <b-form-file
      accept=".csv"
      v-model="file"
      :state="Boolean(file)"
      placeholder="Choose a csv file or drop it here..."
      drop-placeholder="Drop file here..."
      ref="file"
    ></b-form-file>
    <div class="mt-3">Selected file: {{ file ? file.name : "" }}</div>
    <b-button @click="fileUpload" :disabled="file == null">Upload</b-button>
    {{ message }}
  </div>
</template>

<script>
import instance from "../instance.js";
export default {
  data() {
    return {
      file: null,
      message: "",
    };
  },

  methods: {
    fileUpload() {
      const type = "application/vnd.ms-excel";
      if (this.file.type == type) {
        let formData = new FormData();
        formData.append("file", this.file);

        instance
          .post("file-upload", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            this.file = null;
            this.makeToast();
          });
      }
    },
    makeToast() {
      this.$bvToast.toast(`This is toast number`, {
        title: "BootstrapVue Toast",
        autoHideDelay: 5000,
      });
    },
  },
};
</script>

<style>
</style>