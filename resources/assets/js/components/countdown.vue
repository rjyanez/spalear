<template>
    <ul class="vuejs-countdown m-0 p-0 text-white text-center">
        <li  v-if="days > 0" class="d-inline-block mx-2 position-relative ml-0">
            <h3 class="text-white m-0 display-1">{{ days | twoDigits }}</h3>
            <p class=" m-0 text-uppercase mb-0">{{ days > 1 ? 'days' : 'day' }}</p>
        </li>
        <li class="d-inline-block mx-2 position-relative">
            <h3 class="text-white m-0 display-1">{{ hours | twoDigits }}</h3>
            <p class=" m-0 text-uppercase mb-0">{{ hours > 1 ? 'hours' : 'hour' }}</p>
        </li>
        <li class="d-inline-block mx-2 position-relative">
            <h3 class="text-white m-0 display-1">{{ minutes | twoDigits }}</h3>
            <p class=" m-0 text-uppercase mb-0">min</p>
        </li>
        <li class="d-inline-block mx-2 position-relative mr-0">
            <h3 class="text-white m-0 display-1">{{ seconds | twoDigits }}</h3>
            <p class=" m-0 text-uppercase mb-0">sec</p>
        </li>
    </ul>
</template>
<script>
let interval = null;
export default {
    name: 'countdown',
    props: {
        deadline: {
            type: String
        },
        end: {
            type: String
        },
        stop: {
            type: Boolean
        }
    },
    data() {
        return {
            now: Math.trunc((new Date()).getTime() / 1000),
            date: null,
            diff: 0
        }
    },
    created() {
        if (!this.deadline && !this.end) {
            throw new Error("Missing props 'deadline' or 'end'");
        }
        let endTime = this.deadline ? this.deadline : this.end;
        this.date = Math.trunc(Date.parse(endTime.replace(/-/g, "/")) / 1000);
        if (!this.date) {
            throw new Error("Invalid props value, correct the 'deadline' or 'end'");
        }
        interval = setInterval(() => {
            this.now = Math.trunc((new Date()).getTime() / 1000);
        }, 1000);
    },
    computed: {
        seconds() {
            return Math.trunc(this.diff) % 60
        },
        minutes() {
            return Math.trunc(this.diff / 60) % 60
        },
        hours() {
            return Math.trunc(this.diff / 60 / 60) % 24
        },
        days() {
            return Math.trunc(this.diff / 60 / 60 / 24)
        }
    },
    watch: {
        now(value) {
            this.diff = this.date - this.now;
            if(this.diff <= 0 || this.stop){
                this.diff = 0;
                // Remove interval
                clearInterval(interval);
            }
        }
    },
    filters: {
        twoDigits(value) {
            if ( value.toString().length <= 1 ) {
                return '0'+value.toString()
            }
            return value.toString()
        }
    },
    destroyed() {
        clearInterval(interval);
    }
}
</script>
<style>
.vuejs-countdown li h3 {
  font-size: 4rem;
}
.vuejs-countdown li:after {
  content: ":";
  position: absolute;
  top: 0;
  right: -1rem;
  font-size: 4rem;
}
.vuejs-countdown li:last-of-type:after {
  content: "";
}

</style>