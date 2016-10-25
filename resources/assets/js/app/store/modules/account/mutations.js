/* ============
 * User Module Mutations
 * ============
 *
 * todo: add documentation here!
 */

import {
  GET_ACCOUNT,
} from 'app/store/types';

export default {
  [GET_ACCOUNT](state, user) {
    state.user = user.data;
  },
};

