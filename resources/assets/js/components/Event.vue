<template>
  <div class="row">
    <div class="col-md-6">
      <h3 class="page-title">{{ event.title }}</h3>
      <div class="text-muted">{{ event.place }}</div>
      <div class="text-muted">{{ event.start_at | date_range event.end_at }}</div>
    </div>
    <div class="col-md-6">
      <a class="page-action btn btn-primary pull-right" v-link="{ name: 'events.create' }">Novo evento</a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Receita total</h4>
          <h2>{{ 76 | currency '€' }}</h2>
        </div>
        <ul class="list-group">
          <li class="list-group-item">
            <span class="pull-right">137</span> Total de bilhetes vendidos
          </li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Taxa de participação</h4>
          <h2>83,23%</h2>
        </div>
        <ul class="list-group">
          <li class="list-group-item">
            <span class="pull-right">114<span class="text-muted"> / 137 inscritos</span></span>
            Total de participantes
          </li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Vendas nos últimos 5 dias</h4>
          <graph :labels="['08/16','08/17','08/18','08/19','08/20']"
          :values="values"
           ></graph>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Resumo dos bilhetes</h4>
          <table class="table">
            <thead>
              <th>Tipo de bilhete</th>
              <th>Vendas</th>
              <th>Receita</th>
              <th>Taxa de participação</th>
            </thead>
            <tbody>
              <tr>
                <td class="col-md-4">
                  Normal
                </td>
                <td class="col-md-2">
                  38
                </td>
                <td class="col-md-2">
                  {{ 25 | currency '€' }}
                </td>
                <td class="col-md-3">
                  25% <span class="text-muted">(25 participantes)</span>
                </td>
              </tr>
              <tr>
                <td class="col-md-4">
                  Convidado
                </td>
                <td class="col-md-2">
                  62
                </td>
                <td class="col-md-2">
                  {{ 0 | currency '€' }}
                </td>
                <td class="col-md-3">
                  72% <span class="text-muted">(46 participantes)</span>
                </td>
              </tr>
              <tr>
                <td class="col-md-4">
                  Externo
                </td>
                <td class="col-md-2">
                  13
                </td>
                <td class="col-md-2">
                  {{ 51 | currency '€' }}
                </td>
                <td class="col-md-3">
                  90% <span class="text-muted">(13 participantes)</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h4>Sumário de vendas nos últimos 10 dias</h4>
          <graph :labels="['08/10','08/11','08/12','08/13','08/14','08/15','08/16','08/17','08/18','08/19','08/20']"
          :values="values"
           ></graph>
        </div>
      </div>
    </div>
  </div> -->


  <!-- <pre>{{ event | json }}</pre> -->
  <!-- <create-registration :registrations.sync="registrations" :event="event" :registration-types="event.registration_types.data"></create-registration> -->
</template>

<script>
  import CreateRegistration from './Forms/CreateRegistration.vue';
  import Graph from './Util/Graph.vue';
  import '../filters/Date';
  export default {
    data() {
      return {
        // Event data
        event: {},

        values: [{
                type: 'bar',
                  label: "Bilhetes vendidos",
                    data: [5, 10, 3, 2, 10],
                    fill: false,
                    yAxisID: 'y-tickets',
                    borderWidth: 0,
                    backgroundColor: '#2bb074',
            },],

        // Registrations of this event
        registrations: [],
      }
    },
    ready () {
      this.getEvent(this.$route.params.id)
    },
    methods: {
      getEvent (id) {
        this.$http.get('events/' + id).then(response => {
          this.$set('event', response.json().data)
        }).catch(response => {
          this.$router.replace('/404');
        });
      },
    },
    components: {
      CreateRegistration, Graph
    },
  };
</script>
