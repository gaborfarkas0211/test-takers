<template>
  <div>
    <b-tabs card v-model="tabIndex">
      <b-tab @click="getResults" title="Show" active>
        <b-row>
          <b-col cols="12">
            <result-table ref="resultTable"></result-table>
          </b-col>
        </b-row>
      </b-tab>
      <b-tab title="Upload">
        <upload-file></upload-file>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import ResultTable from "./components/ResultTable.vue";
import UploadFile from "./components/UploadFile.vue";

export default {
  components: {
    ResultTable,
    UploadFile,
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
  },
};
</script>

<style>
</style>