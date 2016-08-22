<template>
  <canvas width="640" height="228"></canvas>
</template>

<script>
import Chart from 'chart.js';

export default {
  props: ['labels', 'values', 'color'],

  ready() {
      var data = {
        labels: this.labels,

        datasets: this.values,
      };

      new Chart( this.$el.getContext('2d'), {
          type: "bar",
          data: data,
          options: {
            legend: {
              display: false,
            },
            responsive: true,
            tooltips: {
              mode: 'label'
            },
            scales: {
              xAxes: [{
                gridLines: {
                    display: true
                }
              }],
              yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-tickets",
                    gridLines:{
                        display: false
                    },
                    labels: {
                        show:true,
                    },
                    ticks: {
                      beginAtZero: true,
                    }
                }]
            }
          }
      });
  }
}
</script>
