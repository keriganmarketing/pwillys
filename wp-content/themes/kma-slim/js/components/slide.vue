<template>
    <div class="h-100">
        <div :id="'slide-' + id"
            class="slide"
            :class="{ 'active' :isActive, 'has-content': slideContent != '' }"
            :style="'background-image: url('+image+')'">
            <div class="container" v-if="slideContent !=''">
                <div class="slide-container columns is-justified is-aligned">
                    <div class="column">
                        <slot name="default"></slot>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="slideContent != ''"
            :class="{ 'active' :isActive }"
            class="mobile-description d-md-none">
            <slot name="default"></slot>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            image: {
                type: String,
                default: this.image,
                required: true
            },
            id: {
                type: Number,
                default: this.id
            }
        },

        data(){
            return {
                isActive: false,
                slideContent: ''
            };
        },

        computed: {
            zindex: function(){
                let index = this.id;
                return (20 - index);
            }
        },

        created() {
            if( this.$slots.default ){
            this.slideContent = this.$slots.default;
            }
        }

    }
</script>
<style>
    .slide {
        width:100%;
        -webkit-transition: all linear 1.5s;
        transition: all linear 1.5s;
        position: absolute;
        z-index: -1;
        opacity: 0;
        background-position: center;
        background-size: conver;
    }

    .mobile-description {
        display: none;
        /* background-color: #0060B9; */
    }

    @media screen and (max-width: 768px){
        .slide {
            height: 100%;
        } 
        .slide.has-content {
            height: 100%;
        }
        .mobile-description {
            height: auto;
            display: flex;
            position: absolute;
            bottom: 0;
            /* background-color: #0060B9; */
            line-height: 1.4em;
            transition: opacity linear 1.5s;
            opacity: 0;
        }
        .mobile-description.active {
            opacity: 1;
        }
        .slide .container {
            display: none;
        }
    }

    .slide.active {
        opacity: 1;
        z-index: 0;
    }
    .slide-container {
        align-items: center;
    }

</style>