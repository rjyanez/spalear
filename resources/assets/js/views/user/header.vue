<template>
  <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url(/img/profile-cover.jpg); background-size: cover; background-position: center top;">
    <!-- Mask -->
    <span class="mask bg-gradient-default opacity-8"></span>
    <!-- Header container -->
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-2 text-white">{{ name }}</h1>
                  <p v-if="description" class="text-white mt-0 mb-5">{{ description }}</p>
            </div>
        </div>
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-body" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</div> 
</template>
<script>
export default {
  name: 'header-user',
  props: ['name','description']
}
</script>


