import Vue from 'vue'

let url = 'events{/id}';

export default {
  getActive(request) {
    return Vue.resource(url).get()
      .then((response) => Promise.resolve(response.json().data))
      .catch((error) => Promise.reject(error));
  },
}
