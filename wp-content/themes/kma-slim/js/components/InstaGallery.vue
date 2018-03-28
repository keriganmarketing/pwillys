<template>
    <div class="columns is-multiline is-justified is-aligned is-mobile">
        <div v-for="(photo, index) in photos" class="column is-4">
            <img class="is-block" :src="photo.small" @click="toggleGallery(index)" >
        </div>
        <div class="modal is-active" v-if="showModal">
            <div class="modal-background" @click="toggleGallery"></div>
            <div class="modal-content large" >
                <div class="photo-holder" >
                    <img :src="photos[currentPhoto].large" >
                </div>
                <div class="navigation columns is-mobile is-centered is-justified">
                    <div class="column is-narrow has-text-right"><a class="button is-primary tandelle" @click="previousPhoto">Previous</a></div>
                    <div class="column is-narrow has-text-left"><a class="button is-primary tandelle" @click="nextPhoto">Next</a></div>
                </div>
            </div>
            <button class="modal-close is-large" @click="toggleGallery"></button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            photos: {
                type: Array,
                default: () => []
            }
        },
        data() {
            return {
                currentPhoto: 0,
                showModal: false
            }
        },
        methods: {
            setPhoto(index){
                this.currentPhoto = index;
            },
            toggleGallery(index){
                this.setPhoto(index);
                this.showModal = !this.showModal;
            },
            nextPhoto(){
                this.setPhoto(this.currentPhoto < this.photos.length -1 ? this.currentPhoto + 1 : 0);
            },
            previousPhoto(){
                this.setPhoto(this.currentPhoto > 1 ? this.currentPhoto - 1 : this.photos.length);
            }
        }
    }
</script>

<style scoped>
    img {
        cursor: pointer;
    }

    .modal-content.large {
        width: 960px;
        max-width: 100%;
        overflow: visible;
        max-height: calc(100vh - 100px);
    }

    .photo-holder {
        text-align: center;
        overflow: hidden;
        height: 75vh;
        width: 100%;
        display: block;
    }

    @media screen and (min-width:768px){
        .photo-holder {
            padding-top: 10vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo-holder img {
            max-height: 90%;
            max-width: 90%;
        }
    }

    .navigation {
        padding: 10px;
        height: 10vh;
        width: 100%;
    }
</style>