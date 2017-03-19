/* ============
 * Vuex Actions
 * ============
 *
 * All the actions that can be used
 * inside the store
 */
import * as types from './types';

// Notification
export const notify = ({ commit }, notification) => {
  commit(types.NOTIFY, notification);
};

export const clearNotification = ({ commit }) => {
  commit(types.CLEAR_NOTIFICATION);
};

// Account
export const getAccount = ({ commit }, account) => {
  commit(types.GET_ACCOUNT, account);
};
