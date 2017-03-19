import http from 'axios';
import store from 'app/store/index';

const url = '/users';

export default {
  /**
   * Get account of the user with given id.
   *
   * @param id
   */
  getAccount: (id = 'me') => http.get(`${url}/me`)
    .then(response => Promise.resolve(response.data))
    .then((response) => {
      if (id === 'me') {
        store.dispatch('getAccount', response);
      }
      return Promise.resolve(response);
    })
    .catch(error => Promise.reject(error.body)),
};
