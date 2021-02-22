<template>
  <div>
    <b-row>
      <b-col lg="3" sm="6">
        <b-button @click="addResult"> New Test taker </b-button>
      </b-col>
    </b-row>
    <div v-show="showForm" class="offset-lg-3 col-lg-6">
      <b-row class="pt-2">
        <b-col>
          <label class="mr-sm-2" for="correct-answers">Test Taker name:</label>
          <b-form-input
            id="test-taker"
            @keyup="filterTestTaker"
            v-model="form.testTaker"
            type="text"
            required
            :disabled="!isNewRecord"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <label class="mr-sm-2" for="correct-answers">Correct answers:</label>
          <b-form-input
            id="correct-answers"
            type="number"
            v-model="form.correctAnswers"
            required
            min="0"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <label class="mr-sm-2" for="correct-answers"
            >Incorrect answers:</label
          >
          <b-form-input
            id="incorrect-answers"
            type="number"
            v-model="form.incorrectAnswers"
            required
            min="0"
          />
        </b-col>
      </b-row>

      <b-row class="pt-2 text-center">
        <b-col>
          <b-button
            @click="saveResult"
            :disabled="isDisabled"
            class="mb-2 mr-sm-2 mb-sm-0"
            variant="outline-primary"
            >Save</b-button
          >
          <b-button
            @click="resetForm"
            class="mb-2 mr-sm-2 mb-sm-0"
            variant="outline-danger"
            >Cancel</b-button
          >
        </b-col>
      </b-row>
    </div>
    <b-table
      ref="resultsTable"
      class="text-center pt-2"
      :responsive="true"
      striped
      hover
      :items="items"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
    >
      <template v-slot:cell(actions)="{ item }">
        <b-button
          @click="editResult(item)"
          size="sm"
          class="mr-1 btn-sm btn-block"
          >Edit</b-button
        >
        <b-button
          size="sm"
          @click="deleteResult(item.id)"
          class="btn-block btn-sm"
          >Delete</b-button
        >
      </template>
    </b-table>
    <b-row>
      <b-col sm="12" offset-lg="3">
        <b-pagination
          v-show="items.length"
          v-model="currentPage"
          :total-rows="items.length"
          :per-page="perPage"
          align="fill"
          size="md"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>
    <hr />
    <result-chart v-if="items.length" :items="chartItems"></result-chart>
  </div>
</template>

<script>
import instance from "../instance.js";
import ResultChart from "./ResultChart.vue";

export default {
  data: () => ({
    items: [],
    chartItems: [],
    currentPage: 1,
    perPage: 10,
    fields: [
      { key: "testTaker" },
      { key: "correctAnswers" },
      { key: "incorrectAnswers" },
      { key: "actions", label: "" },
    ],
    form: {
      testTaker: "",
      correctAnswers: 0,
      incorrectAnswers: 0,
    },
    isNewRecord: true,
    showForm: false,
  }),
  components: {
    ResultChart,
  },
  mounted: function () {
    this.getResults();
  },
  watch: {
    currentPage() {
      this.getCurrentTableData();
    },
  },
  computed: {
    isDisabled() {
      return this.form.testTaker == "";
    },
  },
  methods: {
    getResults() {
      let loader = this.$loading.show();
      instance
        .get("result")
        .then((response) => {
          this.items = response.data.data;
          this.getCurrentTableData();
          loader.hide();
        })
    },
    saveResult() {
      let loader = this.$loading.show();
      const url = this.isNewRecord ? "create" : "update?id=" + this.form.id;
      instance
        .post("result/" + url, {
          Result: this.form,
        })
        .then((response) => {
          if (this.isNewRecord) {
            this.items.push(response.data.data);
          } else {
            let index = this.items.findIndex(
              (item) => item.id === response.data.data.id
            );
            this.items[index] = response.data.data;
            this.$refs.resultsTable.refresh();
          }
          this.resetForm();
          this.getCurrentTableData();
          this.$notify({
            type: "success",
            text: "The save was successful.",
          });
        })
        .catch(() => {
          this.$notify({
            type: "error",
            text: "Could not save the result.",
          });
        })
        .then(() => {
          loader.hide();
        });
    },
    addResult() {
      this.resetForm();
      this.isNewRecord = true;
      this.showForm = true;
    },
    editResult(item) {
      this.isNewRecord = false;
      this.form = JSON.parse(JSON.stringify(item));
      this.showForm = true;
    },
    deleteResult(id) {
      let loader = this.$loading.show();
      instance
        .delete("result/delete?id=" + id)
        .then((response) => {
          if (response.data.success) {
            let index = this.items.findIndex((item) => item.id === id);
            this.items.splice(index, 1);
            if (this.form.id == id) {
              this.resetForm();
            }
            this.getCurrentTableData();
            this.$notify({
              type: "success",
              text: "Result deleted successfully.",
            });
          }
        })
        .catch(() => {
          this.$notify({
            type: "error",
            text: "Result could not delete.",
          });
        })
        .then(() => {
          loader.hide();
        });
    },
    resetForm() {
      this.showForm = false;
      this.form.testTaker = "";
      this.form.correctAnswers = 0;
      this.form.incorrectAnswers = 0;
    },
    getCurrentTableData() {
      this.chartItems = [];
      let startIndex = this.currentPage == 1 ? 0 : this.currentPage * 10 - 10;
      let endIndex = this.currentPage * 10;
      for (let i = startIndex; i < endIndex; i++) {
        if (this.items[i]) {
          this.chartItems.push(this.items[i]);
        }
      }
    },
    filterTestTaker(event) {
      const char = String.fromCharCode(event.keyCode);
      if (!/[0-9]/.test(char)) {
        event.preventDefault();
      } else {
        if (event.target.value.length == 2) {
          event.target.value += "-";
        }
        if (event.target.value.length == 6) {
          event.target.value += "-";
        }
      }
    },
  },
};
</script>