<template>
  <table class="table">
    <thead>
      <th class="col-md-4">Nome</th>
      <th class="col-md-2">Tipo de bilhete</th>
      <th class="col-md-1">Data</th>
      <th class="col-md-1 text-center">Ativado</th>
    </thead>
    <tbody>
      <!-- No results -->
      <tr class="no-results" v-show="registrations.length == 0">
        <td class="event-info text-center" colspan="4">
          Não existem inscrições neste evento, <a class="text-primary" v-link="action">crie uma inscrição</a>.
        </td>
      </tr>
      <tr v-for="registration in registrations">
        <td>
          {{ registration.user.data.name }}
        </td>
        <td>
          {{ registration.registration_type.data.type }}
          <span class="label label-warning label-outline">Multa</span>
        </td>
        <td>
          {{ registration.created_at | human_diff }}
        </td>
        <td class="text-center">
          <i :class="['fa', registration.activated ? 'fa-check text-primary' : 'fa-close', { 'text-muted': !registration.fined }]" aria-hidden="true"></i>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
  export default {
    props: {
      action: {
        required: true,
        type: Object,
      },
      registrations: {
        required: true,
        type: Array,
      },
    },
  }
</script>
