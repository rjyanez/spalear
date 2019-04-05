<template>
<div class="text-center mb-3">
  <button v-if="loading" class="float-left btn btn-outline-primary border-0 mt-4" type="button" disabled>
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    Loading...
  </button>
  <button
    v-if="cancel"
    class="ml-2 float-right btn btn-default mt-4 "
    @click="cancelFrom"
    type="button"
    :disabled="loading"
    :class="{ disabled: loading }"
  >
    Cancel
  </button>
  <button 
    @click="submitFrom"
    type="button" 
    :disabled="!submit || loading" 
    :class="{ 'float-right btn btn-success mt-4': true, disabled: !submit || loading }"
  >
    Save
  </button>
</div>  
</template>
<script>

export default {
  name: 'user-submit',
  props: [
    'loading',
    'cancel',
    'submit',
  ],
  methods: {
    cancelFrom(){
      this.$emit("cancel")
    },
    submitFrom(){
      this.$emit("submit")
    }
  }
}
</script>

