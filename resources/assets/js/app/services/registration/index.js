import Vue from 'vue';
import http from 'axios';

const eventsUrl = '/events';
const registrationUrl = '/registrations';

export default {
  /**
   * Get registrations listing.
   *
   * @param page
   * @param filter Limit
   */
  list(eventId, page, limit) {
    return http.get(eventsUrl + '/' + eventId + '/registrations', { params: { page, limit } })
    .then(response => Promise.resolve(response.data))
    .catch(error => Promise.reject(error.response.data));
  },

  /**
   * Store a registration.
   *
   * @param eventId
   * @param data
   */
  store(eventId, data) {
    return http.post(eventsUrl + '/' + eventId + '/registrations', data)
    .then(response => Promise.resolve(response.data))
    .catch(error => Promise.reject(error.response.data));
  },

  /**
   * Resend an email of a given registration.
   *
   * @param registrationId
   */
  resendEmail(registrationId) {
    const resendUrl = `/registrations/${registrationId}/resend`;
    return http.post(resendUrl)
    .then(response => Promise.resolve(response.data))
    .catch(error => Promise.reject(error.response.data));
  },

  /**
   * Delete a registration.
   *
   * @param registrationId
   */
  destroy(registrationId) {
    return http.delete(registrationUrl + '/' + registrationId)
    .then(response => Promise.resolve(response.data))
    .catch(error => Promise.reject(error.response.data));
  },
};
