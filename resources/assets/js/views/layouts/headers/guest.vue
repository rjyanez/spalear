<template>
  <div class="header bg-gradient-default py-7 py-lg-8">
    <div class="container">
        <div class="header-body text-center mb-4">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <h1 class="text-white"> {{ title }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <polygon class="fill-primary" points="2560 0 2560 100 0 100"></polygon>
        </svg>
    </div>
</div>
</template>
<script>
export default {
  name: 'header-guest',
  props: ['title']
}
</script>

