<template>
  <div>
    <notifications position="top center" />
    <b-tabs card v-model="tabIndex">
      <b-tab @click="getResults" title="Show" active>
        <h1>Test takers</h1>
        <result-page ref="resultTable"></result-page>
      </b-tab>
      <b-tab title="Upload">
        <h1>Upload</h1>
        <upload-page></upload-page>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import ResultPage from "./components/ResultPage.vue";
import UploadPage from "./components/UploadPage.vue";

export default {
  components: {
    ResultPage,
    UploadPage,
  },
  data() {
    return {
      tabIndex: 0,
    };
  },
  methods: {
    getResults() {
      if (this.tabIndex !== 0) {
        this.$refs.resultTable.getResults();
      }
    },
    successNotify(text) {
      this.$notify({
        type: "success",
        text: text
      });
    },
    errorNotify(text) {
      this.$notify({
        type: "error",
        text: text
      });
    }
  },
};
</script>

<style>
</style>