<template>
	<div class="header bg-gradient-default py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-2 pt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h1 class="text-white display-2">Coming Soon!</h1>
                        <h2 class="text-lead font-weight-lighter text-white">
                            Our amazing new website is under construction please try again later.
                        </h2>
                        <countdown end="April 15, 2019" />
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-primary" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        <div class="container mt-10 pb-5"></div>
        <div class="bg-primary"></div>
    </div>
    <!-- <calendar/> -->
</template>
<script>
// import calendar from '../components/calendar'
import countdown from '../components/countdown'

export default {
    name: 'home',
    components:{
        countdown
        // calendar
    }
}
</script>