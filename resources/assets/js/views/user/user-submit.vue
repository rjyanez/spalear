<template>
<div class="text-center">
  <button v-if="loading" class="float-left btn btn-outline-primary border-0 mt-4" type="button" disabled>
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    Loading...
  </button>
  <button
    v-if="action !== 'show' && cancel"
    class="ml-2 float-right btn btn-default mt-4 "
    @click="setAction('show')"
    type="button"
    :disabled="loading"
    :class="{ disabled: loading }"
  >
    Cancel
  </button>
  <button v-if="isEdit" type="submit" :disabled="!submit || loading" :class="{ 'float-right btn btn-success mt-4': true, disabled: !submit || loading }">
    Save
  </button>
  <buttonConfirmation
    v-on:confirmation-success="submitFrom"
    :messages="[
      {confirmation: false, icon: false , text: 'Destroy User', class: 'btn-danger'},
      {
        confirmation: true,
        options: [
          {icon: false , text: 'Yes', class: ' btn-outline-warning', accept: true},
          {icon: false , text: 'Not', class: ' btn-warning', accept: false},                  
        ]
      },
      {confirmation: false, icon: false , text: 'Ok!', class: 'btn-success'}                           
    ]"
    v-if="action === 'destroy'"
    :disabled="loading"
    :css="{ 'float-right btn   mt-4': true, disabled: loading }"
  />
</div>  
</template>
<script>
import buttonConfirmation from "../../components/buttonConfirmation";

export default {
  name: 'user-submit',
  components: {
    buttonConfirmation
  },
  props: [
    'loading',
    'action',
    'cancel',
    'isEdit',
    'submit',
  ],
  methods: {
    setAction(action){
      this.$emit("setAction",action)
    },
    submitFrom(){
      this.$emit("submitFrom")
    }
  }
}
</script>

