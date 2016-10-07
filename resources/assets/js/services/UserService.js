import Vue from 'vue'

const url = 'users{/id}'

export default {

  /**
   * Find user by the given id.
   *
   * @param id
   */
  find (id) {
    return Vue.resource(url, { id }, {
      before: function (request) {
        console.log(request)
      }
    }).get()
      .then((response) => Promise.resolve(response.json()))
      .catch((error) => Promise.reject(error))
  }
}
