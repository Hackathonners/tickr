import Vue from 'vue';
import http from 'axios';

const url = '/events';

export default {
  /**
   * Get events listing.
   *
   * @param page
   * @param filter Filter for listing
   */
  list(page = 1, filter) {
    return http.get(url, { params: { page, filter } })
      .then(response => Promise.resolve(response.data))
      .catch(error => Promise.reject(error.response.data));
  },

  /**
   * Get a given event.
   *
   * @param eventId
   * @param stats Include event statistics
   */
  get(eventId, stats) {
    stats = stats ? 1 : 0;
    return http.get(url + '/' + eventId, { params: { stats } })
      .then(response => Promise.resolve(response.data))
      .catch(error => Promise.reject(error.response.data));
  },

  /**
   * Store an event.
   *
   * @param data
   */
  store(data) {
    return http.post(url, data)
      .then(response => Promise.resolve(response.data))
      .catch(error => Promise.reject(error.response.data));
  },

  /**
   * Delete an event.
   *
   * @param eventId
   */
  destroy(eventId) {
    return http.delete(url + '/' + eventId)
      .then(response => Promise.resolve(response.data))
      .catch(error => Promise.reject(error.response.data));
  },
};
