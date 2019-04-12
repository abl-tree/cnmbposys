<template>
  <div class="layer w-100 pX-20 pY-30 pB-20">
    <highchart :options="chartOptions"/>
  </div>
</template>

<script>
import Highcharts from "highcharts";
import exportingInit from "highcharts/modules/exporting";
import { Chart } from "highcharts-vue";
exportingInit(Highcharts);
export default {
  components: {
    highchart: Chart
  },
  props: ["userId"],
  data() {
    return {
      chartOptions: {
        chart: {
          type: "column"
        },
        title: {
          text: "Work Graph",
          align: "left"
        },
        xAxis: {
          categories: [
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12",
            "2019/04/10",
            "2019/04/11",
            "2019/04/12"
          ],
          stackLabels: {
            enabled: true
          }
        },
        yAxis: {
          title: {
            text: "Total Time Spent"
          }
        },

        plotOptions: {
          column: {
            stacking: "normal"
          }
        },
        series: [
          {
            data: [
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1,
              1
            ],
            name: "None-billable",
            color: "#8cc34c",
            opacity: 0.8,
            stack: 0 // sample data
          },
          {
            data: [
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5,
              8,
              7.5,
              5.5
            ],
            name: "Billable",
            color: "#8cc34c", // sample data
            stack: 0
          },
          {
            data: [
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3,
              0.5,
              0.4,
              0.3
            ],
            name: "None-billable(OT)",
            color: "#ffbc75",
            opacity: 0.8,
            stack: 1 // sample data
          },
          {
            data: [
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2,
              2.5,
              1.8,
              3.2
            ],
            name: "Billable(OT)",
            color: "#ffbc75", // sample data
            stack: 1
          }
        ]
      }
    };
  },
  mounted() {},
  methods: {
    sumOFobject: function(input) {
      if (toString.call(input) !== "[object Array]") return false;

      var total = 0;
      for (var i = 0; i < input.length; i++) {
        if (isNaN(input[i])) {
          continue;
        }
        total += Number(input[i]);
      }
      return total;
    }
  }
};
</script>
