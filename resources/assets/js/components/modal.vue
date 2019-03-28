<template>
<div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
      	<slot name="header"></slot>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<slot name="body"></slot>        
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
	name: "modal",
	props: [
		'id'
	]
}
</script>