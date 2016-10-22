import Vue from 'vue'
import store from 'app/store'

const url = 'users{/id}'

export default {
  /**
   * Get account of the user with given id.
   *
   * @param id
   */
  getAccount: (id = 'me') => {
    return Vue.resource(url, { id }).get()
      .then((response) => Promise.resolve(response.json()))
      .then((response) => {
        if (id === 'me') {
          store.dispatch('getAccount', response)
        }
        return Promise.resolve(response);
      })
      .catch((error) => Promise.reject(error))
  }
}
