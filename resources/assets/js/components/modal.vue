<template>
<div 
  :class="{'modal modal-bg fade': true, 'show d-block': show, 'd-none': !show}" 
  tabindex="-1" 
  role="dialog"  
  aria-hidden="true" 
  @click="close"

>
  <div class="modal-dialog modal-lg modal-dialog-centered  modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <slot name="header"></slot>
        <button type="button" class="close" @click="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <slot name="body"></slot>
        </div>
      </div>
      <div class="modal-footer">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  name: 'modal',
  props: {
    'show': {
      type: Boolean,
      default: false
    }
  },
 methods: {
    close() {
      this.$emit('close')
    }
  }
}
</script>
