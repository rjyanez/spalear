<template>
  <div>
    <div class="btn-group" role="group" v-if="currentMessage.confirmation">
      <button
        v-for="button in currentMessage.options"
        type="button"
        data-toggle="tooltip" 
        data-placement="top" 
        :title="button.text"
        :class="[ css, stepsComplete? 'confirmation__button--complete' : '', button.class ]"
        :disabled='stepsComplete'
        v-on:click='(button.accept)? incrementStep() : reset()'>
        <i v-if="button.icon" :class="button.icon"></i>
        <template v-else>{{ button.text }}</template>    
      </button>      
    </div>
    <button
      v-else
      type="button"
      data-toggle="tooltip" 
      data-placement="top" 
      :title="currentMessage.text"
      :class="[ css, stepsComplete? 'confirmation__button--complete' : '', currentMessage.class ]"
      :disabled='stepsComplete'
      v-on:click='incrementStep()'>
      <i v-if="currentMessage.icon" :class="currentMessage.icon"></i>
      <template v-else>{{ currentMessage.text }}</template>    
    </button>
  </div>
</template>

<script>
  export default {
    name: 'button-confirmation',
    props: {
      messages: Array,
      css: {
        type: String,
        default: 'confirmation__button'
      },
    },
    data() {
      return {
        defaultSteps: [
          'Click to confirm',
          'Are you sure?',
          '✔',
        ],
        currentStep: 0,
      }
    },
    computed: {
      messageList() {
        return this.messages ? this.messages : this.defaultSteps
      },
      currentMessage() {
        return this.messageList[this.currentStep]
      },
      lastMessageIndex() {
        return this.messageList.length - 1
      },
      stepsComplete() {
        return this.currentStep === this.lastMessageIndex
      }
    },
    methods: {
      incrementStep() {
        this.currentStep++
        if (this.stepsComplete) {
          this.$emit('confirmation-success')
        }
        else {
          this.$emit('confirmation-incremented')
        }
      },
      reset() {
        this.currentStep = 0
        this.$emit('confirmation-reset')
      },
    },
  }
</script>