<template>
  <div class="form-group">
    <button type="submit" :class="['btn', 'btn-primary', loading ? 'disabled' : '']" @click.prevent="callback">
      <slot v-if="!loading"></slot>
      <span v-else><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> {{ message }}</span>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    callback: {
      required: true,
      type: Function
    },
    loading: {
      required: true,
      type: Boolean
    },
    message: {
      type: String
    }
  }
}
</script>
