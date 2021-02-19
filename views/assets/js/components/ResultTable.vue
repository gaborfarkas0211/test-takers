<template>
  <div>
    <b-row>
      <b-col cols="3">
        <b-button @click="showForm = !showForm"> New Test taker </b-button>
      </b-col>
    </b-row>
    <div v-show="showForm">
      <b-form>
        <b-form-group label="Test Taker:" label-for="test-taker">
          <b-form-input
            id="test-taker"
            v-model="form.testTaker"
            type="text"
            required
            :disabled="!isNewRecord"
          ></b-form-input>
        </b-form-group>

        <b-form-group label="Correct Answers:" label-for="correct-answers">
          <b-form-input
            id="correct-answers"
            type="number"
            v-model="form.correctAnswers"
            required
            min="0"
          ></b-form-input>
        </b-form-group>

        <b-form-group label="Incorrect Answers:" label-for="incorrect-answers">
          <b-form-input
            id="incorrect-answers"
            type="number"
            v-model="form.incorrectAnswers"
            required
            min="0"
          ></b-form-input>
        </b-form-group>
      </b-form>

      <b-button @click="onSave()" variant="primary">Submit</b-button>
    </div>
    <b-table
      :responsive="true"
      :per-page="perPage"
      striped
      hover
      :items="items"
      :fields="fields"
      class="text-center"
      :current-page="currentPage"
    >
      <template v-slot:cell()="{ value }">
        <template>{{ value }}</template>
      </template>

      <template v-slot:cell(actions)="{ item, index }">
        <b-button @click="onEdit(item)" size="sm" class="mr-1">Edit</b-button>
        <b-button size="sm" @click="onDelete(item.id, index)">Delete</b-button>
      </template>
    </b-table>
    <b-row>
      <b-col lg="6" sm="12" offset-lg="3"
        ><b-pagination
          v-show="items.length"
          v-model="currentPage"
          :total-rows="items.length"
          :per-page="perPage"
          align="fill"
          size="md"
          class="my-0"
        ></b-pagination
      ></b-col>
    </b-row>
    <result-chart v-if="items.length" :items="chartItems"></result-chart>
  </div>
</template>

<script>
import instance from "../instance.js";
import ResultChart from "./ResultChart.vue";

export default {
  data: function () {
    return {
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
    };
  },
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
  methods: {
    getResults() {
      instance.get("result").then((response) => {
        this.items = response.data.data;
        this.getCurrentTableData();
      });
    },
    onEdit(item) {
      this.isNewRecord = false;
      this.showForm = true;
      this.form = item;
    },
    onSave() {
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
          }
          this.showForm = false;
        });
    },
    onDelete(id, index) {
      instance.delete("result/delete?id=" + id).then((response) => {
        if (response.data.success) {
          this.items.splice(index, 1);
          this.getCurrentTableData();
        }
      });
    },
    onReset() {
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
  },
};
</script>