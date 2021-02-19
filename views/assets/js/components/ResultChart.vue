<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  props: ["items"],
  data: () => ({
    chartData: {},
    colors: ["#696969", "#bada55", "#7fe5f0", "#ff80ed", "#407294", "#cbcba9", "#420420", "#133337", "#5ac18e", "#ffa500"], 
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [
          {
            ticks: {
              autoSkip: true,
              maxTicksLimit: 10,
            },
          },
        ],
      },
    },
  }),
  watch: {
    chartData(to, from) {
      this.renderChart(this.chartData, this.options);
    },
    items() {
      this.createChartObject();
      this.renderChart(this.chartData, this.options);
    },
  },

  mounted() {
    this.createChartObject();
    this.renderChart(this.chartData, this.options);
  },
  methods: {
    createChartObject() {
      let datasets = [];
      this.items.forEach((element, index) => {
        let object = {
          label: element.testTaker,
          backgroundColor: this.colors[index],
          data: [element.correctAnswers, element.incorrectAnswers],
        };
        datasets.push(object);
      });
      let data = {
        labels: ["Correct Answers", "Incorrect Answers"],
        datasets: datasets,
      };
      this.chartData = data;
    },
  },
};
</script>

<style>
</style>