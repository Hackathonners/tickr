/* ============
 * User Module Mutations
 * ============
 *
 * todo: add documentation here!
 */

import {
  GET_ACCOUNT,
} from 'app/store/mutation-types';

export default {
  [GET_ACCOUNT](state, user) {
    state.user = user.data;
  },
};

