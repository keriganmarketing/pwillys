<template>
    <article class="message" v-show="isVisible" >
        <div class="message-body">
            <button class="delete is-pulled-right" @click="hideMessage"></button>
            <slot></slot>
        </div>
    </article>
</template>

<script>
    export default {
        props: [
            'title'
        ],

        data(){
            return {
                isVisible: true
            };
        },

        methods: {
            hideMessage(){
                this.isVisible = !this.isVisible;
            }
        }
    }
</script>