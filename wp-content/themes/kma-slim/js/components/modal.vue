<template>
    <div class="modal is-active" v-if="showModal">
        <div class="modal-background" @click="toggleModal"></div>
        <div class="modal-content large" >
            <slot></slot>
            <div class="photo-holder" v-html="modalContent"></div>
        </div>
        <button class="modal-close is-large" @click="toggleModal"></button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showModal: false,
                modalContent: ''
            }
        },
        methods: {
            toggleModal(content){
                this.showModal = !this.showModal;
                if(this.$parent.modalOpen !== ''){
                    this.$parent.modalOpen = ''
                }
                this.modalContent = content;
            }
        },
        mounted() {
            let currentModal = this;

            this.$parent.$on('toggleModal', function (modal,content) {
                currentModal.toggleModal(content);
            });

        }
    }
</script>

<style scoped>
    .modal-content.large {
        width: 960px;
        max-width: 100%;
        overflow: hidden;
    }

    .photo-holder {
        text-align: center;
        overflow: hidden;
    }

    .photo-holder img {
        max-height: 90vh;
        max-width: 90vh;
    }
</style>