<template>
    <div :id="'slide-' + id"
         class="slide"
         :class="{ 'active' :isActive }"
         :style="'background-image: url('+image+')'">
        <div class="container">
            <div class="slide-container columns is-justified is-aligned">
                <div class="column is-narrow">
                    <slot></slot>
                </div>
            </div>
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
                isActive: false
            };
        },

        computed: {
            zindex: function(){
                let index = this.id;
                return (20 - index);
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
    .slide.active {
        opacity: 1;
        z-index: 0;
    }
    .slide-container {
        align-items: center;
    }

</style>