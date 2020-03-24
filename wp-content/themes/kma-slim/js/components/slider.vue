<template>
    <div class="h-100">
        <div class="slider">
            <div class="slider-left icon is-large" @click="clickPrev" >
                <i class="fa fa-angle-left is-large" aria-hidden="true"></i>
            </div>

            <div class="slides" @mouseover="pauseSlide" @mouseleave="unpauseSlide">
                <slot></slot>
            </div>

            <div class="slider-right icon is-large" @click="clickNext" >
                <i class="fa fa-angle-right is-large" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data(){
            return {
                slides: [],
                activeSlide: 0,
                paused: false
            };
        },

        mounted(){
            this.slides = this.$children;
            setInterval(() => { if(this.paused === false){ this.nextSlide() } }, 6000)
            this.slides[0].isActive = true
        },

        methods: {

            nextSlide(){
                this.slides[this.activeSlide]._data.isActive = false
                if(this.activeSlide === this.slides.length-1){
                    this.activeSlide = -1
                }
                this.activeSlide++;
                this.slides[this.activeSlide]._data.isActive = true
            },

            prevSlide(){
                this.slides[this.activeSlide]._data.isActive = false
                this.activeSlide--;
                if(this.activeSlide === -1){
                    this.activeSlide = this.slides.length-1
                }
                this.slides[this.activeSlide]._data.isActive = true
            },

            clickNext(){
                this.nextSlide();
                this.pauseSlide()
            },

            clickPrev(){
                this.prevSlide();
                this.pauseSlide()
            },

            pauseSlide(){
                this.paused = true;
            },

            unpauseSlide(){
                this.paused = false;
            }

        }

    }
</script>
<style>

    .slider {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .slide,
    .slider,
    .slides {
        height:100%;
        -webkit-transition: opacity linear 1.5s;
        transition:opacity linear 1.5s;
        background-size: cover;
    }

    .slides {
        flex-grow: 1;
    }

    .slider-right,
    .slider-left {
        position: absolute;
        z-index: 30;
    }

    .slider-right {
        right:0;
    }
</style>