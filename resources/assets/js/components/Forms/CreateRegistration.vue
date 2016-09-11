<template>
  <div v-show="$loadingRouteData" class="row page-header">
    <loading :loading="$loadingRouteData" message="A carregar evento..."></loading>
  </div>

  <div v-show="!$loadingRouteData" class="page-content">
    <div class="row page-header">
      <div class="col-md-12">
        <span class="page-title">Nova inscrição em {{ event.title }}</span>
      </div>
    </div>

    <!-- Form Errors -->
    <errors :error="error"></errors>

    <!-- New Registration Form -->
    <!-- Registration details -->
    <div class="fieldset">
      <span class="label label-primary step">1</span>
      <span class="title">Dados do participante</span>
    </div>

    <div class="form-group">
      <label for="registration-email">E-mail do participante</label>
      <input type="email" class="form-control" id="registration-email" v-model="registration.email">
      <span class="help-block small"><strong>Nota:</strong> O bilhete do evento será enviado para este e-mail.</span>
    </div>
    <div class="form-group">
      <label for="registration-name">Nome do participante</label>
      <input type="text" class="form-control" id="registration-name" v-model="registration.name">
    </div>
    <div class="form-group">
      <label for="registration-notes">Outras informações</label>
      <textarea class="form-control" id="registration-notes" v-model="registration.notes" rows="5"></textarea>
    </div>

    <!-- Registration Type details -->
    <div class="fieldset">
      <span class="label label-primary step">2</span>
      <span class="title">Bilhete do participante</span>
    </div>

    <div class="form-group">
      <label for="registration-type">Tipo de bilhete</label>
      <select class="form-control" v-model="registration.registration_type">
        <option disabled selected>Selecionar o tipo de bilhete</option>
        <option v-for="registrationType in event.registration_types.data" :value="registrationType.id">
          {{ registrationType.type }}
        </option>
      </select>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" value="1" v-model="registration.fined" :checked="registration.fined"> Adicionar valor de multa ao preço do bilhete.</label>
    </div>

    <div class="fieldset">
      <span class="label label-primary step">3</span>
      <span class="title">Bom trabalho, está quase.</span>
    </div>

    <table v-show="registration.registration_type" class="table">
      <thead>
        <th>Descritivo</th>
        <th>Valor</th>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-4">
            Bilhete do tipo "{{ getRegistrationTypeData(registration.registration_type, 'type') }}"
          </td>
          <td class="col-md-2 ">
            {{ getRegistrationTypeData(registration.registration_type, 'price') | price }}
          </td>
        </tr>
        <tr v-show="registration.fined">
          <td class="col-md-4">
            Multa
          </td>
          <td class="col-md-2 ">
            {{ getRegistrationTypeData(registration.registration_type, 'fine') | price }}
          </td>
        </tr>
      </tbody>
      <tfoot>
        <th>Total a pagar</th>
        <th>{{ getPaidValue() | price }}</th>
      </tfoof>
    </table>

    <div class="form-group">
      <button class="btn btn-primary" @click.prevent="save">Inscrever</button>
    </div>
  </div>
</template>

<script>
  function formatRepo (repo) {
      if (repo.loading) return repo.text;
      var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
      if (repo.description) {
        markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
      }
      markup += "<div class='select2-result-repository__statistics'>" +
        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
        "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
      "</div>" +
      "</div></div>";
      return markup;
    }
    function formatRepoSelection (repo) {
      return repo.full_name || repo.text;
    }

  import '../../directives/Select';
  import '../../filters/Price';
  import Errors from '../Layout/Errors.vue';
  import EventService from '../../services/EventService.js';
  import Loading from '../Util/Loading.vue';
  import moment from 'moment';

  export default {
    data() {
      return {
        event: {
          name: null,
          registration_types: {
            data: [],
          }
        },
        registration: {},
        error: null,

        selectOptions: {
          ajax: {
            url: "https://api.github.com/search/repositories",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data, params) {
              // parse the results into the format expected by Select2
              // since we are using custom formatting functions we do not need to
              // alter the remote JSON data, except to indicate that infinite
              // scrolling can be used
              params.page = params.page || 1;

              return {
                results: data.items,
                pagination: {
                  more: (params.page * 30) < data.total_count
                }
              };
            },
            cache: true
          },
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1,
          templateResult: formatRepo, // omitted for brevity, see the source of this page
          templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        }
      }
    },
    ready () {
      this.$loadingRouteData = true;
      EventService.get(this.$route.params.id).then(event => {
        this.$set('event', event);
        this.resetRegistrationState();
        this.$loadingRouteData = false;
      });
    },
    methods: {
      resetRegistrationState() {
        return this.registration = {
          name: null,
          email: null,
          fined: moment().isBetween(this.event.start_at, this.event.end_at, 'day'),
          registration_type: null,
          notes: null,
        };
      },
      save() {
        this.$http.post('events/' + this.event.id + '/registrations', this.registration).then(response => {
          this.registrations.push(this.registration);
          this.resetRegistrationState();
        }).catch( response => {
          this.error = JSON.parse(response.body).error;
        })
      },
      getRegistrationTypeData (registrationTypeId, field) {
        let registrationType = this.event.registration_types.data.find(r => r.id == registrationTypeId);
        return registrationType && field in registrationType ? registrationType[field] : 0;
      },
      getPaidValue() {
        if(!this.registration.registration_type)
          return 0;

        let registrationType = this.event.registration_types.data.find(r => r.id == this.registration.registration_type);
        let value = registrationType.price;
        value += this.registration.fined ? registrationType.fine : 0;

        return value;
      }
    },
    components: {
      Loading, Errors
    },
  };
</script>
