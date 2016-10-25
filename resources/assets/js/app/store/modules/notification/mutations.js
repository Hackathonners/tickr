/* ============
 * User Module Mutations
 * ============
 *
 * todo: add documentation here!
 */

import {
  NOTIFY,
  CLEAR_NOTIFICATION,
} from 'app/store/types';

export default {
  [NOTIFY](state, notification) {
    state.data = notification;
  },

  [CLEAR_NOTIFICATION](state) {
    state.data = null;
  },
};

