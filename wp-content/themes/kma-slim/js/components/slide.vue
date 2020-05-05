<template>
    <div class="h-100">
        <div :id="'slide-' + id"
            class="slide"
            :class="{ 'active' :isActive }"
            :style="'background-image: url('+image+')'">
            <div class="container" v-if="$slots.content !=''">
                <div class="slide-container columns is-justified is-aligned">
                    <div class="column">
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="$slots.content !=''"
            :class="{ 'active' :isActive }"
            class="mobile-description d-md-none">
            <slot></slot>
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

    .mobile-description {
        display: none;
        /* background-color: #0060B9; */
    }

    @media screen and (max-width: 768px){
        .slide {
            height: 60%;
        } 
        .mobile-description {
            display: flex;
            position: absolute;
            bottom: 0;
            /* background-color: #0060B9; */
            line-height: 1.4em;
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